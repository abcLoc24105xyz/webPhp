<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class LoginController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 30;

    public function showLoginForm()
    {
        try {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return view('admin.auth.login');
        } catch (Throwable $e) {
            Log::error('Admin login form error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'email'    => 'required|email|max:255',
                'password' => 'required|string|min:6|max:255',
            ], [
                'email.email'       => 'Email không hợp lệ!',
                'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự!',
            ]);

            $email = $validated['email'];
            $throttleKey = 'admin|' . Str::lower($email) . '|' . $request->ip();

            // ✅ Kiểm tra rate limit
            if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                $minutes = floor($seconds / 60);
                $secs = $seconds % 60;

                Log::warning("Admin login throttled: {$email} from {$request->ip()}");

                return back()
                    ->withErrors(['email' => "Tài khoản bị tạm khóa do đăng nhập sai quá nhiều lần. Thử lại sau {$minutes} phút {$secs} giây."])
                    ->withInput($request->only('email'));
            }

            $credentials = $request->only('email', 'password');

            // ✅ Kiểm tra admin có tồn tại không
            $admin = \App\Models\Admin::where('email', $email)->first();
            if (!$admin) {
                RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
                Log::warning("Admin login failed: email not found - {$email} from {$request->ip()}");

                return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác!'])
                    ->withInput($request->only('email'));
            }

            // ✅ Kiểm tra tài khoản bị khóa
            if (!in_array($admin->status, [1, '1'])) {
                RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
                Log::warning("Admin login attempted with inactive account: {$email}");

                return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa! Liên hệ quản trị viên.'])
                    ->withInput($request->only('email'));
            }

            // ✅ Xác thực mật khẩu
            if (!Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
                Log::warning("Admin login failed: wrong password - {$email} from {$request->ip()}");

                return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác!'])
                    ->withInput($request->only('email'));
            }

            // ✅ Đăng nhập thành công
            Auth::guard('admin')->login($admin, $request->filled('remember'));
            RateLimiter::clear($throttleKey);

            Log::info("Admin logged in: {$email} from {$request->ip()}");

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Admin login validation error: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput($request->only('email'));

        } catch (\Exception $e) {
            Log::error('Admin login exception: ' . $e->getMessage(), [
                'email' => $request->email ?? 'unknown',
                'ip'    => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Có lỗi hệ thống xảy ra, vui lòng thử lại sau!')
                ->withInput($request->only('email'));

        } catch (Throwable $e) {
            Log::critical('Admin login critical error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace'     => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Có lỗi nghiêm trọng xảy ra, vui lòng liên hệ quản trị viên!')
                ->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info("Admin logged out: {$admin?->email} from {$request->ip()}");

            return redirect()->route('admin.login')
                ->with('success', 'Đăng xuất thành công!');

        } catch (\Exception $e) {
            Log::error('Admin logout error: ' . $e->getMessage());

            // Đảm bảo logout dù có lỗi
            Auth::guard('admin')->logout();
            $request->session()->invalidate();

            return redirect()->route('admin.login')
                ->with('warning', 'Đăng xuất thành công nhưng có lỗi nhỏ xảy ra.');

        } catch (Throwable $e) {
            Log::critical('Admin logout critical error: ' . $e->getMessage());

            Auth::guard('admin')->logout();
            $request->session()->invalidate();

            return redirect()->route('admin.login')
                ->with('success', 'Đã đăng xuất.');
        }
    }
}