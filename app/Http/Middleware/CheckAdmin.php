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
            return redirect()->route('login');
        }

        if (!$user->kich_hoat) {
            abort(403, 'Tài khoản của bạn đã bị khóa.');
        }

        $quyen = $user->quyen_han;

        $allowed = match ($level) {
            'admin'  => $quyen >= 3,
            'ketoan' => $quyen >= 2,
            'staff'  => $quyen >= 1,
            default  => false,
        };

        if (!$allowed) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}