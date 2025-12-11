@extends('layouts.app')
@section('title', 'Đổi mật khẩu')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Đổi mật khẩu</h2>
            <p class="text-gray-600 mt-2">Vui lòng nhập mật khẩu hiện tại và mật khẩu mới</p>
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

        {{-- ✅ FIX: Password strength indicator --}}
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
            <strong>📋 Yêu cầu mật khẩu mới:</strong>
            <ul class="mt-2 space-y-1 text-xs">
                <li>✓ Tối thiểu 8 ký tự</li>
                <li>✓ Chứa chữ hoa (A-Z)</li>
                <li>✓ Chứa chữ thường (a-z)</li>
                <li>✓ Chứa số (0-9)</li>
                <li>✓ Chứa ký tự đặc biệt (@$!%*?&)</li>
            </ul>
        </div>

        {{-- ✅ FIX: Form route đúng --}}
        <form action="{{ route('password.change.update') }}" method="POST" novalidate>
            @csrf
            <div class="space-y-5">
                {{-- Mật khẩu hiện tại --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu hiện tại</label>
                    <input type="password" 
                           name="current_password" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <small class="text-red-600 text-xs block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Mật khẩu mới --}}
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

                {{-- Xác nhận mật khẩu mới --}}
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
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-4 rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('profile.show') }}" class="text-purple-600 hover:underline">
                ← Quay lại hồ sơ
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

        passwordInput.addEventListener('input', checkPasswordMatch);
        passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    });
</script>
@endsection