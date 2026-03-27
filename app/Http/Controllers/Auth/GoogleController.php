<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MergesGioHang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    use MergesGioHang;

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['ten_dang_nhap' => 'Đăng nhập Google thất bại, vui lòng thử lại.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            if (!$user->kich_hoat) {
                return redirect()->route('login')
                    ->withErrors(['ten_dang_nhap' => 'Tài khoản của bạn đã bị khóa.']);
            }
        } else {
            $user = User::create([
                'ho_ten'        => $googleUser->getName(),
                'ten_dang_nhap' => $this->taoTenDangNhap($googleUser->getName()),
                'email'         => $googleUser->getEmail(),
                'mat_khau'      => bcrypt(Str::random(32)),
                'hinh_anh'      => $googleUser->getAvatar(),
                'quyen_han'     => User::USER,
                'kich_hoat'     => true,
            ]);
        }

        Auth::login($user, true);

        $this->mergeGioHang(session()->getId());

        return redirect('/')->with('success', 'Đăng nhập bằng Google thành công!');
    }

    private function taoTenDangNhap(string $hoTen): string
    {
        $base = Str::slug(str_replace(' ', '_', $hoTen), '_');
        $base = substr($base, 0, 30);

        $username = $base;
        $i = 1;

        while (User::where('ten_dang_nhap', $username)->exists()) {
            $username = $base . '_' . $i;
            $i++;
        }

        return $username;
    }
}