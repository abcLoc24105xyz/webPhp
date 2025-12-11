<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // 1. Trang nhập email
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // 2. Gửi OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.'
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            // ✅ FIX: Kiểm tra user status
            if ($user->status != 1) {
                Log::warning('Password reset attempt on unverified account', [
                    'email' => $request->email,
                    'status' => $user->status
                ]);

                return back()->with('error', 'Tài khoản chưa được xác minh. Vui lòng kiểm tra email để xác minh tài khoản trước.');
            }

            // ✅ FIX: Kiểm tra nếu user login bằng Google
            if ($user->provider === 'google') {
                Log::info('Password reset attempt on Google account', [
                    'email' => $request->email
                ]);

                return back()->with('error', 'Tài khoản Google không thể đặt lại mật khẩu. Vui lòng sử dụng Google để đăng nhập.');
            }

            $otp = rand(100000, 999999);

            $user->update([
                'otp_code'   => $otp,
                'otp_expiry' => Carbon::now()->addMinutes(5),
            ]);

            Mail::to($user->email)->send(new OtpMail($otp, $user->full_name, 'reset'));

            Log::info('OTP sent for password reset', [
                'email' => $request->email,
                'timestamp' => now()
            ]);

            return redirect()->route('password.reset.combined')
                           ->with('email', $user->email)
                           ->with('success', 'Mã OTP đã được gửi đến email của bạn!');

        } catch (\Exception $e) {
            Log::error('Error sending OTP', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể gửi OTP. Vui lòng thử lại sau.');
        }
    }

    // 3. Hiển thị form kết hợp: OTP + Mật khẩu mới
    public function showCombinedForm()
    {
        if (!session('email')) {
            return redirect()->route('password.combined')
                           ->with('error', 'Phiên đặt lại mật khẩu đã hết hạn.');
        }
        return view('auth.passwords.combined');
    }

    // 4. Xử lý đặt lại mật khẩu (OTP + mật khẩu mới)
    public function processCombined(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'otp'                   => 'required|digits:6',
            'password'              => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/' // ✅ FIX: Mật khẩu mạnh
            ],
            'password_confirmation' => 'required'
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải có 6 chữ số.',
            'password.regex' => 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&).',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.'
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            // ✅ FIX: String comparison an toàn
            if ((string)$request->otp !== (string)$user->otp_code) {
                Log::warning('Wrong OTP for password reset', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);

                return back()->withErrors(['otp' => 'Mã OTP không đúng. Vui lòng thử lại.']);
            }

            // ✅ FIX: Kiểm tra OTP hết hạn
            if (!$user->otp_expiry || Carbon::now()->isAfter($user->otp_expiry)) {
                Log::warning('OTP expired for password reset', [
                    'email' => $request->email
                ]);

                return back()->with('error', 'Mã OTP đã hết hạn! Vui lòng gửi lại mã OTP mới.')
                           ->with('otpExpired', true);
            }

            // ✅ FIX: Kiểm tra mật khẩu mới không trùng với cũ
            if (Hash::check($request->password, $user->password)) {
                return back()->with('email', $request->email)
                            ->withErrors(['password' => 'Mật khẩu mới không được trùng với mật khẩu cũ!']);
            }

            $user->update([
                'password'          => Hash::make($request->password),
                'password_changed_at' => now(),
                'otp_code'          => null,
                'otp_expiry'        => null,
            ]);

            $request->session()->forget('email');

            Log::info('Password reset successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'timestamp' => now()
            ]);

            return redirect()->route('login')
                           ->with('success', 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.');

        } catch (\Exception $e) {
            Log::error('Error processing password reset', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Lỗi xử lý đặt lại mật khẩu. Vui lòng thử lại.');
        }
    }
}