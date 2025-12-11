<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // ← HÀM QUAN TRỌNG NHẤT – FIX LỖI 500 KHI CHƯA LOGIN
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return redirect()->route('auth.login');
    }
}