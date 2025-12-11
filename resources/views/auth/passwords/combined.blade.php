{{-- resources/views/auth/passwords/combined.blade.php --}}
@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - GhienCine')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Đặt lại mật khẩu</h2>
            <p class="text-gray-600 mt-2">
                Mã OTP đã được gửi đến: <strong>{{ session('email') ?? '' }}</strong>
            </p>
        </div>

        {{-- ✅ FIX: Success message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ FIX: Error message --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6 text-center font-medium animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                {{ session('error') }}
            </div>
        @endif

        {{-- ✅ FIX: OTP expired warning --}}
        @if(session('otpExpired'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center">
                <strong>⏰ Mã hết hạn!</strong><br>
                Mã OTP đã hết hạn. Vui lòng gửi lại mã OTP mới.
            </div>
        @endif

        {{-- ✅ FIX: Password strength indicator --}}
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
            <strong>📋 Yêu cầu mật khẩu:</strong>
            <ul class="mt-2 space-y-1 text-xs">
                <li>✓ Tối thiểu 8 ký tự</li>
                <li>✓ Chứa chữ hoa (A-Z)</li>
                <li>✓ Chứa chữ thường (a-z)</li>
                <li>✓ Chứa số (0-9)</li>
                <li>✓ Chứa ký tự đặc biệt (@$!%*?&)</li>
            </ul>
        </div>

        {{-- ✅ FIX: Form route đúng (password.reset) --}}
        <form method="POST" action="{{ route('password.reset') }}" novalidate>
            @csrf
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">

            <div class="space-y-5">
                <!-- OTP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã OTP (6 số)</label>
                    <input type="text" 
                           name="otp" 
                           maxlength="6" 
                           inputmode="numeric"
                           required 
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-2xl tracking-widest focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('otp') border-red-500 @enderror">
                    @error('otp')
                        <small class="text-red-600 text-xs block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Mật khẩu mới -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                    <input type="password" 
                           name="password" 
                           required
                           id="passwordInput"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('password') border-red-500 @enderror">
                    @error('password')
                        <small class="text-red-600 text-xs block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nhập lại mật khẩu mới</label>
                    <input type="password" 
                           name="password_confirmation" 
                           required
                           id="passwordConfirmInput"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('password') border-red-500 @enderror">
                </div>

                {{-- ✅ FIX: Password match indicator --}}
                <div id="passwordMatchIndicator" class="p-3 bg-gray-100 rounded-lg text-sm hidden">
                    <span id="matchText" class="text-gray-600">Mật khẩu xác nhận không khớp</span>
                </div>

                <button type="submit"
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Không nhận được mã? </span>
            <a href="{{ route('password.request') }}" class="text-purple-600 font-medium hover:underline">
                Gửi lại OTP
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('passwordInput');
        const passwordConfirmInput = document.getElementById('passwordConfirmInput');
        const submitBtn = document.getElementById('submitBtn');
        const matchIndicator = document.getElementById('passwordMatchIndicator');
        const matchText = document.getElementById('matchText');

        function checkPasswordMatch() {
            if (passwordInput.value.length === 0 || passwordConfirmInput.value.length === 0) {
                matchIndicator.classList.add('hidden');
                submitBtn.disabled = true;
                return;
            }

            if (passwordInput.value === passwordConfirmInput.value) {
                matchIndicator.classList.remove('hidden');
                matchText.textContent = '✓ Mật khẩu khớp';
                matchText.className = 'text-green-600 font-medium';
                submitBtn.disabled = false;
            } else {
                matchIndicator.classList.remove('hidden');
                matchText.textContent = '✗ Mật khẩu xác nhận không khớp';
                matchText.className = 'text-red-600 font-medium';
                submitBtn.disabled = true;
            }
        }

        // ✅ FIX: Chỉ cho phép số trong input OTP
        const otpInput = document.querySelector('input[name="otp"]');
        otpInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            checkPasswordMatch();
        });

        passwordInput.addEventListener('input', checkPasswordMatch);
        passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    });
</script>
@endsection