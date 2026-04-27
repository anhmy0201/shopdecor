<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\MergesGioHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use MergesGioHang;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau'      => 'required|string',
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'mat_khau.required'      => 'Vui lòng nhập mật khẩu.',
        ]);

        $login    = $request->ten_dang_nhap;
        $matKhau  = $request->mat_khau;
        $remember = $request->boolean('remember');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'ten_dang_nhap';

        $credentials = [
            $field      => $login,
            'password'  => $matKhau,
            'kich_hoat' => true,
        ];

        // FIX: Lấy session ID của guest TRƯỚC khi attempt()
        // Sau khi attempt() thành công + regenerate(), session ID đã đổi
        // → sessionId cũ dùng để merge giỏ hàng guest vào tài khoản
        $sessionId = $request->session()->getId();

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $this->mergeGioHang($sessionId);

            if (Auth::user()->isAdmin() || Auth::user()->isStaff()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('ten_dang_nhap', 'remember'))
            ->withErrors([
                'ten_dang_nhap' => 'Tên đăng nhập/email hoặc mật khẩu không đúng.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đã đăng xuất thành công.');
    }
}