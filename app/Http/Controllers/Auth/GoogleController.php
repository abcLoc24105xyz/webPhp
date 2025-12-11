<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // ✅ FIX: Add try-catch to redirect
    public function redirect()
    {
        try {
            Log::info('User attempting Google login redirect');
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google redirect failed', [
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login')
                           ->with('error', 'Không thể kết nối tới Google. Vui lòng thử lại sau.');
        }
    }

    // Google gọi lại sau khi người dùng đồng ý
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google callback failed', [
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login')
                           ->with('error', 'Đăng nhập Google thất bại! Vui lòng thử lại.');
        }

        try {
            // Tìm user theo provider_id (Google ID)
            $user = User::where('provider', 'google')
                        ->where('provider_id', $googleUser->id)
                        ->first();

            if (!$user) {
                // Nếu chưa có → tìm theo email (trường hợp từng đăng ký bình thường)
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // ✅ FIX: Cập nhật Google ID cho tài khoản cũ
                    // Nhưng kiểm tra nếu account đã link với provider khác
                    if ($user->provider && $user->provider !== 'google') {
                        return redirect()->route('login')
                                       ->with('error', 'Email này đã được liên kết với nhà cung cấp khác.');
                    }

                    $user->update([
                        'provider'        => 'google',
                        'provider_id'     => $googleUser->id,
                        'provider_avatar' => $googleUser->avatar,
                    ]);

                    Log::info('Google account linked to existing user', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                } else {
                    // ✅ FIX: Tạo tài khoản mới - generate phone hoặc set nullable
                    $user = User::create([
                        'full_name'         => $googleUser->name ?? 'Google User',
                        'email'             => $googleUser->email,
                        'phone'             => null, // ✅ FIX: Set nullable hoặc generate
                        'password'          => bcrypt(Str::random(16)),
                        'provider'          => 'google',
                        'provider_id'       => $googleUser->id,
                        'provider_avatar'   => $googleUser->avatar,
                        'status'            => 1, // ✅ Auto-active vì verify bằng Google
                        'email_verified_at' => now(),
                    ]);

                    Log::info('New user created via Google', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                }
            }

            Auth::login($user, true);

            Log::info('User successfully logged in via Google', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->route('home')
                           ->with('success', 'Đăng nhập bằng Google thành công!');

        } catch (\Exception $e) {
            Log::error('Error processing Google login', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                           ->with('error', 'Lỗi xử lý đăng nhập. Vui lòng thử lại.');
        }
    }
}