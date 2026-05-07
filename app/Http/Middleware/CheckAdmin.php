<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next, string $level = 'admin')
    {
        $user = $request->user();

        if (!$user) {
            // Khách vãng lai — cho qua nếu là route mua hàng (không bắt đăng nhập)
            if ($level === 'khach_hang') {
                return $next($request);
            }
            return redirect()->route('login');
        }

        if (!$user->kich_hoat) {
            abort(403, 'Tài khoản của bạn đã bị khóa.');
        }

        $quyen = $user->quyen_han;

        $allowed = match ($level) {
            'admin'      => $quyen >= 4,                     // Cấp 4 — Admin toàn quyền
            'giam_doc'   => $quyen >= 3,                     // Cấp 3+ — Giám đốc trở lên
            'quanli'     => $quyen >= 2,                     // Cấp 2+ — Quản lí trở lên
            'staff'      => $quyen >= 1,                     // Cấp 1+ — Nhân viên trở lên
            'khach_hang' => $quyen === 0 || $quyen >= 4,     // Cấp 0 (khách hàng) hoặc Admin (cấp 4)
            default      => false,
        };

        if (!$allowed) {
            if ($level === 'khach_hang') {
                abort(403, 'Tài khoản nội bộ không được phép mua hàng.');
            }
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}