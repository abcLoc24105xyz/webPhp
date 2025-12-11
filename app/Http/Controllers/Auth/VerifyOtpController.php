<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
use Carbon\Carbon;

class VerifyOtpController extends Controller
{
    // ✅ FIX: Giới hạn brute-force OTP
    protected $maxOtpAttempts = 5;
    protected $maxResendAttempts = 3;
    protected $lockoutMinutes = 15;

    public function show(Request $request)
    {
        // Nếu không có email trong session → quay lại đăng ký
        if (!$request->session()->has('email')) {
            return redirect()->route('register')
                           ->with('error', 'Phiên xác minh đã hết hạn. Vui lòng đăng ký lại.');
        }

        $email = $request->session()->get('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')
                           ->with('error', 'Tài khoản không tồn tại.');
        }

        // Kiểm tra xem OTP đã hết hạn hay chưa
        $otpExpired = false;
        if ($user->otp_expiry && Carbon::now()->isAfter($user->otp_expiry)) {
            $otpExpired = true;
        }

        // ✅ FIX: Kiểm tra đã bị khóa do nhập sai OTP quá nhiều lần
        $cacheKey = "otp_wrong_attempts_{$email}";
        $wrongAttempts = Cache::get($cacheKey, 0);
        $isLocked = $wrongAttempts >= $this->maxOtpAttempts;

        return view('auth.verify-otp', [
            'otpExpired' => $otpExpired,
            'email' => $email,
            'isLocked' => $isLocked,  // ✅ Truyền biến để view check
            'wrongAttempts' => $wrongAttempts,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải có 6 chữ số.',
        ]);

        $email = $request->session()->get('email');
        if (!$email) {
            return redirect()->route('register')
                           ->with('error', 'Email không hợp lệ.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')
                           ->with('error', 'Tài khoản không tồn tại.');
        }

        // ✅ FIX: Kiểm tra đã bị khóa do nhập sai OTP quá nhiều lần
        $cacheKey = "otp_wrong_attempts_{$email}";
        $wrongAttempts = Cache::get($cacheKey, 0);

        if ($wrongAttempts >= $this->maxOtpAttempts) {
            Log::warning('OTP verification locked - too many wrong attempts', [
                'email' => $email,
                'ip' => $request->ip(),
                'attempts' => $wrongAttempts
            ]);

            return back()->with('error', 'Bạn đã nhập sai OTP quá nhiều lần. Vui lòng gửi lại mã OTP mới.')
                        ->with('isLocked', true);
        }

        // ✅ KIỂM TRA OTP ĐÃ HẾT HẠN CHƯA (trước khi kiểm tra mã)
        if (!$user->otp_expiry || Carbon::now()->isAfter($user->otp_expiry)) {
            Log::info('OTP verification attempt - OTP expired', [
                'email' => $email,
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Mã OTP đã hết hạn! Vui lòng gửi lại mã OTP mới.')
                        ->with('otpExpired', true);
        }

        // ✅ KIỂM TRA MÃ OTP CÓ ĐÚNG KHÔNG (dùng === thay vì !=)
        if ((string)$request->otp !== (string)$user->otp_code) {
            $wrongAttempts++;
            Cache::put($cacheKey, $wrongAttempts, $this->lockoutMinutes * 60);

            Log::warning('Wrong OTP attempt', [
                'email' => $email,
                'ip' => $request->ip(),
                'attempt_number' => $wrongAttempts
            ]);

            return back()->withErrors(['otp' => "Mã OTP không đúng. Vui lòng thử lại. ({$wrongAttempts}/{$this->maxOtpAttempts})"])
                        ->with('wrongAttempts', $wrongAttempts);
        }

        // ✅ XÃC MINH THÀNH CÔNG
        $user->update([
            'status'     => 1,
            'otp_code'   => null,
            'otp_expiry' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        // ✅ FIX: Clear cache khi verify thành công
        Cache::forget($cacheKey);
        Cache::forget("otp_resend_attempts_{$email}");

        Auth::login($user);
        $request->session()->forget('email');

        Log::info('User email verified successfully', [
            'user_id' => $user->id,
            'email' => $email,
            'timestamp' => now()
        ]);

        return redirect()->route('home')
                        ->with('success', 'Xác minh thành công! Chào mừng bạn đến với GhienCine');
    }

    // Gửi lại OTP
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        $email = $request->session()->get('email') ?? $request->email;

        if (!$email) {
            return redirect()->route('register')
                           ->with('error', 'Vui lòng cung cấp email.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này không tồn tại.']);
        }

        // Nếu đã xác minh rồi
        if ($user->status == 1) {
            return back()->with('error', 'Tài khoản này đã được xác minh. Vui lòng đăng nhập.');
        }

        // ✅ FIX: Giới hạn số lần gửi lại OTP
        $resendCacheKey = "otp_resend_attempts_{$email}";
        $resendAttempts = Cache::get($resendCacheKey, 0);

        if ($resendAttempts >= $this->maxResendAttempts) {
            Log::warning('OTP resend blocked - too many attempts', [
                'email' => $email,
                'ip' => $request->ip(),
                'attempts' => $resendAttempts
            ]);

            return back()->with('error', 'Bạn đã gửi lại OTP quá nhiều lần. Vui lòng thử lại sau 15 phút.');
        }

        // Tạo OTP mới
        $otp = rand(100000, 999999);

        try {
            $user->update([
                'otp_code'   => $otp,
                'otp_expiry' => Carbon::now()->addMinutes(5),
            ]);

            Mail::to($email)->send(new OtpMail($otp, $user->full_name, 'register'));

            // Tăng số lần gửi lại
            Cache::put($resendCacheKey, $resendAttempts + 1, $this->lockoutMinutes * 60);

            // ✅ Clear cache nhập sai OTP khi gửi lại mã mới
            Cache::forget("otp_wrong_attempts_{$email}");

            Log::info('OTP resent successfully', [
                'email' => $email,
                'timestamp' => now()
            ]);

            // Cập nhật email trong session
            $request->session()->put('email', $email);

            return back()->with('success', 'Mã OTP mới đã được gửi đến email của bạn!')
                        ->with('otpExpired', false);

        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể gửi lại OTP. Vui lòng thử lại sau.');
        }
    }
}