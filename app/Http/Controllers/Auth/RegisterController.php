<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|digits:10|regex:/^0[3-9][0-9]{8}$/|unique:users,phone',
            'password'  => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // ✅ FIX: Mật khẩu phải có chữ hoa, thường, số, ký tự đặc biệt
            ],
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email tối đa 255 ký tự.',
            'phone.digits' => 'Số điện thoại phải có đúng 10 số.',
            'phone.regex'  => 'Số điện thoại không hợp lệ (ví dụ: 0901234567).',
            'phone.unique' => 'Số điện thoại này đã được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&).',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        // ✅ FIX: Kiểm tra email và phone trong 1 query
        $existingUser = User::where('email', $request->email)
                            ->orWhere('phone', $request->phone)
                            ->first();

        if ($existingUser) {
            if ($existingUser->email === $request->email) {
                if ($existingUser->status == 1) {
                    // Email đã được kích hoạt
                    return back()->withErrors(['email' => 'Email này đã được đăng ký.'])
                                ->withInput($request->only('full_name', 'email', 'phone'));
                } else {
                    // Tài khoản chưa kích hoạt (status = 2), cập nhật lại thay vì xóa
                    // ✅ FIX: Update OTP thay vì delete
                    $otp = rand(100000, 999999);
                    
                    $existingUser->update([
                        'full_name'  => $request->full_name,
                        'password'   => Hash::make($request->password),
                        'otp_code'   => $otp,
                        'otp_expiry' => Carbon::now()->addMinutes(5),
                        'status'     => 2,
                    ]);

                    try {
                        Mail::to($request->email)->send(new OtpMail($otp, $request->full_name, 'register'));
                        
                        session()->put('email', $request->email);
                        
                        Log::info('OTP resent for existing unverified user', [
                            'email' => $request->email,
                            'timestamp' => now()
                        ]);

                        return redirect()->route('verify-otp.show')
                                        ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để nhập mã OTP.');
                    } catch (\Exception $e) {
                        Log::error('Failed to send OTP email', [
                            'email' => $request->email,
                            'error' => $e->getMessage()
                        ]);

                        return back()->with('error', 'Không thể gửi email OTP. Vui lòng thử lại sau.')
                                    ->withInput($request->only('full_name', 'email', 'phone'));
                    }
                }
            }

            if ($existingUser->phone === $request->phone) {
                if ($existingUser->status == 1) {
                    return back()->withErrors(['phone' => 'Số điện thoại này đã được đăng ký.'])
                                ->withInput($request->only('full_name', 'email', 'phone'));
                }
            }
        }

        // Tạo OTP mới
        $otp = rand(100000, 999999);

        try {
            // ✅ FIX: Tạo user trước, rồi gửi email
            User::create([
                'full_name'   => $request->full_name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'password'    => Hash::make($request->password),
                'otp_code'    => $otp,
                'otp_expiry'  => Carbon::now()->addMinutes(5),
                'status'      => 2, // chưa xác minh
            ]);

            // Gửi email OTP
            Mail::to($request->email)->send(new OtpMail($otp, $request->full_name, 'register'));

            Log::info('User registered successfully', [
                'email' => $request->email,
                'phone' => $request->phone,
                'timestamp' => now()
            ]);

            session()->put('email', $request->email);

            return redirect()->route('verify-otp.show')
                            ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để nhập mã OTP.');

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể hoàn tất đăng ký. Vui lòng thử lại sau.')
                        ->withInput($request->only('full_name', 'email', 'phone'));
        }
    }
}