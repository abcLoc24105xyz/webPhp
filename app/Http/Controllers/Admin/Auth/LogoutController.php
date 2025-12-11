<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Đăng xuất tài khoản Admin
     */
    public function logout(Request $request)
    {
        // Đăng xuất guard admin
        Auth::guard('admin')->logout();

        // Hủy phiên login
        $request->session()->invalidate();

        // Tạo lại CSRF token mới
        $request->session()->regenerateToken();

        // Điều hướng về trang đăng nhập admin
        return redirect()->route('admin.login')
            ->with('success', 'Đăng xuất thành công!');
    }
}
