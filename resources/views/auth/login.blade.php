@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Chào mừng trở lại!</h2>
            <p class="text-gray-600 mt-2">Đăng nhập để tiếp tục đặt vé</p>
        </div>

        {{-- ✅ FIX: Success message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 text-center font-medium shadow-md animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ FIX: Status message --}}
        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 text-center font-medium shadow-md animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ session('status') }}
            </div>
        @endif

        {{-- Error messages --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            {{-- ✅ FIX: Hidden field để lưu redirect URL --}}
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="your@email.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('email') border-red-500 bg-red-50 @enderror">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                    <input type="password" 
                           name="password" 
                           required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('password') border-red-500 bg-red-50 @enderror">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ FIX: Remember me checkbox --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('password.request') }}" 
                       class="text-sm text-purple-600 hover:text-purple-800 font-medium hover:underline transition">
                        Quên mật khẩu?
                    </a>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-4 rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 text-lg">
                    Đăng nhập ngay
                </button>
            </div>
        </form>

        <div class="mt-8">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500 font-medium">Hoặc tiếp tục với</span>
                </div>
            </div>

            <div class="mt-6">
                {{-- ✅ FIX: Google login - use link, not form --}}
                <a href="{{ route('auth.google') }}" 
                   class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-bold py-4 rounded-lg transition transform hover:scale-105 shadow-lg">
                    <img src="https://www.google.com/favicon.ico" alt="Google" class="w-6 h-6">
                    <span>Đăng nhập bằng Google</span>
                </a>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Chưa có tài khoản?
                <a href="{{ route('register') }}" class="text-purple-600 font-bold hover:underline">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
@endsection