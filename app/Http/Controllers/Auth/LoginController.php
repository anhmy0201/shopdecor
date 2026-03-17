<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Giohang;
use App\Models\ChitietGiohang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập — cho phép cả email lẫn ten_dang_nhap
     */
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

        // Xác định field: email hay ten_dang_nhap
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'ten_dang_nhap';

        // Thử đăng nhập
        $credentials = [
            $field     => $login,
            'password' => $matKhau,  // Laravel Auth luôn map sang cột password
            'kich_hoat'=> true,      // chặn tài khoản bị khóa
        ];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Merge giỏ hàng session vào giỏ user
            $this->mergeGioHang($request->session()->getId());

            // Redirect theo quyền
            if (Auth::user()->isAdmin() || Auth::user()->isStaff()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        // Đăng nhập thất bại
        return back()
            ->withInput($request->only('ten_dang_nhap', 'remember'))
            ->withErrors([
                'ten_dang_nhap' => 'Tên đăng nhập/email hoặc mật khẩu không đúng.',
            ]);
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

        // Xóa giỏ session cũ
        $gioHangSession->chitiets()->delete();
        $gioHangSession->delete();
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đã đăng xuất thành công.');
    }
}