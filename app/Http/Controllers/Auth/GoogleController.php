<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Giohang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Chuyển hướng sang Google
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Google callback — tạo hoặc tìm user rồi đăng nhập
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['ten_dang_nhap' => 'Đăng nhập Google thất bại, vui lòng thử lại.']);
        }

        // Tìm user theo email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Đã có tài khoản — kiểm tra còn active không
            if (!$user->kich_hoat) {
                return redirect()->route('login')
                    ->withErrors(['ten_dang_nhap' => 'Tài khoản của bạn đã bị khóa.']);
            }
        } else {
            // Chưa có — tạo tài khoản mới từ Google
            $user = User::create([
                'ho_ten'        => $googleUser->getName(),
                'ten_dang_nhap' => $this->taoTenDangNhap($googleUser->getName()),
                'email'         => $googleUser->getEmail(),
                'mat_khau'      => bcrypt(Str::random(32)), // mật khẩu ngẫu nhiên
                'hinh_anh'      => $googleUser->getAvatar(),
                'quyen_han'     => User::USER,
                'kich_hoat'     => true,
            ]);
        }

        Auth::login($user, true); // remember = true

        // Merge giỏ hàng session vào giỏ user
        $this->mergeGioHang(session()->getId());

        return redirect('/')->with('success', 'Đăng nhập bằng Google thành công!');
    }

    
    private function mergeGioHang(string $sessionId)
    {
        $gioHangSession = Giohang::where('session_id', $sessionId)->first();
        if (!$gioHangSession || $gioHangSession->chitiets()->count() === 0) return;

        $gioHangUser = Giohang::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($gioHangSession->chitiets as $ct) {
            $existing = $gioHangUser->chitiets()
                ->where('sanpham_id', $ct->sanpham_id)
                ->when(
                    $ct->bienthe_id === null,
                    fn($q) => $q->whereNull('bienthe_id'),
                    fn($q) => $q->where('bienthe_id', $ct->bienthe_id)
                )
                ->first();

            if ($existing) {
                $existing->increment('so_luong', $ct->so_luong);
            } else {
                $gioHangUser->chitiets()->create(
                    $ct->only(['sanpham_id', 'bienthe_id', 'so_luong', 'gia'])
                );
            }
        }

        $gioHangSession->chitiets()->delete();
        $gioHangSession->delete();
    }

    /**
     * Tạo ten_dang_nhap từ tên Google — đảm bảo không trùng
     */
    private function taoTenDangNhap(string $hoTen): string
    {
        // "Nguyễn Văn An" → "nguyen_van_an"
        $base = Str::slug(str_replace(' ', '_', $hoTen), '_');
        $base = substr($base, 0, 30);

        $username = $base;
        $i = 1;

        // Nếu trùng thì thêm số vào sau
        while (User::where('ten_dang_nhap', $username)->exists()) {
            $username = $base . '_' . $i;
            $i++;
        }

        return $username;
    }
}