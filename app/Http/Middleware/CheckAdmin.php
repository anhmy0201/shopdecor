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

        if ($level === 'admin' && !$user->isAdmin()) {
            abort(403);
        }

        if ($level === 'staff' && !$user->isStaff()) {
            abort(403);
        }

        return $next($request);
    }
}