{{-- resources/views/auth/passwords/email.blade.php --}}
@extends('layouts.app')

@section('title', 'Quên mật khẩu - GhienCine')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Quên mật khẩu?</h2>
            <p class="text-gray-600 mt-2">Nhập email để nhận mã OTP đặt lại mật khẩu</p>
        </div>

        {{-- ✅ FIX: Success/Status message --}}
        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ session('status') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ FIX: Error message --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('email') border-red-500 @enderror">
                    @error('email')
                        <small class="text-red-600 text-xs block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                    Gửi mã OTP
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-purple-600 font-medium hover:underline">
                ← Quay lại đăng nhập
            </a>
        </div>
    </div>
</div>
@endsection