<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Cấu hình chống brute-force
    protected $maxAttempts = 5;     // Sai 5 lần → khóa
    protected $decayMinutes = 5;   // Khóa 15 phút

    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
        ], [
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không hợp lệ.',
            'email.max'         => 'Email không được vượt quá 255 ký tự.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max'      => 'Mật khẩu không được vượt quá 255 ký tự.',
        ]);

        $email = $request->email;
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        // Kiểm tra đã bị khóa chưa?
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('Login attempt blocked - rate limited', [
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return back()
                ->withErrors(['email' => 'Tài khoản bị tạm khóa do đăng nhập sai quá nhiều lần. Thử lại sau ' . floor($seconds / 60) . ' phút ' . ($seconds % 60) . ' giây.'])
                ->withInput($request->only('email'));
        }

        $user = \App\Models\User::where('email', $email)->first();

        // ✅ FIX: Kiểm tra tài khoản có tồn tại không
        if (!$user) {
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);

            Log::warning('Login attempt - account not found', [
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return back()->withErrors(['email' => 'Tài khoản không tồn tại. Vui lòng kiểm tra email hoặc đăng ký tài khoản mới.'])
                        ->withInput($request->only('email'));
        }

        // ✅ FIX: Timing attack - kiểm tra password với fake hash nếu user không tồn tại
        if (!Hash::check($request->password, $user->password ?? Hash::make('fake-password-for-timing-attack'))) {
            // Tăng số lần thử sai
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);

            Log::warning('Failed login attempt - wrong password', [
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return back()->withErrors(['email' => 'Mật khẩu không đúng. Vui lòng thử lại.'])
                        ->withInput($request->only('email'));
        }

        // ✅ FIX: Tách logic status để rõ hơn
        if ($user->status == 0) {
            // Status = 0: Bị khóa bởi admin
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            
            Log::warning('Login attempt - account locked by admin', [
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa bởi quản lý rạp. Vui lòng liên hệ để mở khóa.'])
                        ->withInput($request->only('email'));
        }

        if ($user->status == 2) {
            // Status = 2: Chưa xác minh email
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            
            Log::info('Login attempt - account not verified', [
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return back()->withErrors(['email' => 'Tài khoản chưa được xác minh. Vui lòng kiểm tra email để xác minh tài khoản.'])
                        ->withInput($request->only('email'));
        }

        if ($user->status != 1) {
            // Status khác 1 ngoài những case trên
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            
            return back()->withErrors(['email' => 'Tài khoản không hợp lệ. Vui lòng liên hệ quản lý rạp.'])
                        ->withInput($request->only('email'));
        }

        RateLimiter::clear($throttleKey);

        Log::info('Successful login', [
            'user_id' => $user->id,
            'email' => $email,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        Auth::login($user, $request->boolean('remember'));

        // ƯU TIÊN: ?next=... → rồi mới đến intended → rồi mới về home
        $nextUrl = $request->query('next'); // Lấy từ ?next=...

        if ($nextUrl && filter_var($nextUrl, FILTER_VALIDATE_URL) && str_starts_with($nextUrl, url('/'))) {
            // Bảo mật: chỉ cho phép redirect nội bộ
            return redirect($nextUrl)->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->full_name . '!');
        }

        // Nếu không có next → dùng Laravel intended (tự động lưu khi bị chặn bởi auth middleware)
        return redirect()->intended(route('home'))
            ->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->full_name . '!');
    }
}