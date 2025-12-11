<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = Auth::user();

        Log::info('User logged out', [
            'user_id' => $user->id ?? null,
            'email' => $user->email ?? null,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // ✅ FIX: Redirect to login, not home
        return redirect()->route('login')
                        ->with('status', 'Bạn đã đăng xuất thành công. Hẹn gặp lại!');
    }
}