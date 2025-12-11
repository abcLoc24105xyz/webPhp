<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();

        // ✅ FIX: Kiểm tra nếu user đăng nhập bằng provider (Google, Facebook, etc)
        if ($user->provider && $user->provider !== null) {
            Log::info('User attempted to change password on social login account', [
                'user_id' => $user->id,
                'provider' => $user->provider
            ]);

            return redirect()->route('profile.show')
                           ->with('error', 'Tài khoản ' . ucfirst($user->provider) . ' không thể đổi mật khẩu. Bạn có thể tiếp tục sử dụng ' . ucfirst($user->provider) . ' để đăng nhập!');
        }

        return view('auth.passwords.change');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // ✅ FIX: Chặn lại ở đây để an toàn (phòng trường hợp bypass)
        if ($user->provider && $user->provider !== null) {
            Log::warning('Direct attempt to change password on social account', [
                'user_id' => $user->id,
                'provider' => $user->provider,
                'ip' => $request->ip()
            ]);

            return redirect()->route('profile.show')
                           ->with('error', 'Tài khoản ' . ucfirst($user->provider) . ' không hỗ trợ đổi mật khẩu!');
        }

        $request->validate([
            'current_password'      => 'required',
            'password'              => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/' // ✅ FIX: Mật khẩu mạnh
            ],
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới tối thiểu 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&).',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        // ✅ FIX: Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Wrong current password attempt', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng. Vui lòng thử lại.']);
        }

        // ✅ FIX: Kiểm tra mật khẩu mới không trùng với cũ
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mật khẩu mới không được trùng với mật khẩu cũ!']);
        }

        $user->update([
            'password'           => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        Log::info('User successfully changed password', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()
        ]);

        return redirect()->route('profile.show')
                       ->with('success', 'Đổi mật khẩu thành công!');
    }
}