<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập tài khoản quản trị!');
        }

        $admin = Auth::guard('admin')->user();

        // Hỗ trợ cả số và chuỗi (dùng cho mọi trường hợp)
        $status = $admin->status;

        if (in_array($status, [0, '0', 'inactive', 'banned', '3'])) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Tài khoản quản trị của bạn đã bị khóa hoặc không được phép truy cập!');
        }

        return $next($request);
    }
}