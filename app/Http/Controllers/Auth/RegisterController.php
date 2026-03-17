<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Giohang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten'        => 'required|string|max:255',
            'ten_dang_nhap' => 'required|string|max:50|unique:users,ten_dang_nhap|alpha_dash',
            'email'         => 'required|email|max:255|unique:users,email',
            'so_dien_thoai' => 'nullable|string|max:15',
            'mat_khau'      => 'required|string|min:6|confirmed',
        ], [
            'ho_ten.required'          => 'Vui lòng nhập họ tên.',
            'ten_dang_nhap.required'   => 'Vui lòng nhập tên đăng nhập.',
            'ten_dang_nhap.unique'     => 'Tên đăng nhập đã tồn tại.',
            'ten_dang_nhap.alpha_dash' => 'Tên đăng nhập chỉ gồm chữ, số, dấu gạch ngang.',
            'email.required'           => 'Vui lòng nhập email.',
            'email.unique'             => 'Email này đã được đăng ký.',
            'mat_khau.required'        => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min'             => 'Mật khẩu tối thiểu 6 ký tự.',
            'mat_khau.confirmed'       => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'ho_ten'        => $request->ho_ten,
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau'      => Hash::make($request->mat_khau),
            'quyen_han'     => User::USER,
            'kich_hoat'     => true,
        ]);

        // Tự động đăng nhập sau khi đăng ký
        Auth::login($user);

        // Merge giỏ hàng session vào giỏ user
        $this->mergeGioHang(session()->getId());

        return redirect('/')->with('success', 'Chào mừng ' . $user->ho_ten . '! Đăng ký thành công.');
    }

    /**
     * Merge giỏ hàng session vào giỏ hàng user sau khi đăng ký
     */
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
}