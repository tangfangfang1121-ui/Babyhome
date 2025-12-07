<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // 检查：如果用户已登录 且 角色是 admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 否则踢回首页或报错
        abort(403, '非管理员禁止入内');
    }
}