<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

trait ThrottlesLogins
{
    protected $maxAttempts  = 5;   // Sai 5 lần thì khóa
    protected $decayMinutes = 5;   // Khóa 5 phút

    protected function hasTooManyAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts
        );
    }

    protected function incrementAttempts(Request $request)
    {
        RateLimiter::hit(
            $this->throttleKey($request), $this->decayMinutes * 60
        );
    }

    protected function clearAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        // ←←← Dòng này giải quyết 100% trường hợp "khóa mãi không mở"
        if ($seconds <= 0) {
            RateLimiter::clear($this->throttleKey($request));
            $seconds = 0;
        }

        $minutes  = floor($seconds / 60);
        $secs     = $seconds % 60;
        $timeText = $seconds > 0
            ? ($minutes > 0 ? "$minutes phút " : '') . ($secs > 0 ? "$secs giây" : '')
            : "vài giây";

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau $timeText.",
            ]);
    }
}