@extends('layouts.app')

@section('title', 'Xác minh OTP')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full mx-auto flex items-center justify-center">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold mt-4 text-gray-800">Xác minh email</h2>
            <p class="text-gray-600 mt-2">
                Chúng tôi đã gửi mã OTP 6 chữ số đến<br>
                {{-- ✅ FIX: Xử lý encoding UTF-8 --}}
                <strong>{{ session('email') ?? $email ?? 'email của bạn' }}</strong>
            </p>
        </div>

        {{-- ✅ FIX: Success message --}}
        @if ($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm text-center animate-pulse">
                <strong>✓ Thành công!</strong><br>
                {{ $message }}
            </div>
        @endif

        {{-- ✅ FIX: Error message --}}
        @if ($message = Session::get('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                {{ $message }}
            </div>
        @endif

        {{-- ✅ FIX: OTP wrong attempts warning --}}
        @if ($errors->has('otp'))
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg text-sm text-center animate-pulse">
                <strong>⚠ Cảnh báo!</strong><br>
                {{ $errors->first('otp') }}
            </div>
        @endif

        {{-- ✅ FIX: Email error --}}
        @if ($errors->has('email'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center animate-pulse">
                <strong>✗ Lỗi!</strong><br>
                {{ $errors->first('email') }}
            </div>
        @endif

        {{-- ✅ FIX: Locked warning --}}
        @if ($isLocked ?? false)
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center">
                <strong>🔒 Tài khoản bị khóa!</strong><br>
                Bạn đã nhập sai OTP quá nhiều lần. Vui lòng gửi lại mã OTP mới.
            </div>
        @endif

        {{-- ✅ FIX: OTP expired warning --}}
        @if ($otpExpired ?? false)
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center">
                <strong>⏰ Mã hết hạn!</strong><br>
                Mã OTP đã hết hạn. Vui lòng gửi lại mã OTP mới.
            </div>
        @endif

        {{-- ✅ FIX: Đồng hồ đếm ngược --}}
        <div class="mb-6 text-center">
            <p class="text-sm text-gray-600 mb-2">Mã OTP sẽ hết hạn trong:</p>
            <div class="text-4xl font-bold text-purple-600" id="timer">05:00</div>
            <p class="text-xs text-gray-500 mt-1">Gửi lại mã sau khi hết hạn</p>
        </div>

        {{-- ✅ FIX: Form xác minh OTP --}}
        <form action="{{ route('verify-otp.verify') }}" method="POST" id="otpForm">
            @csrf

            <div class="mb-6">
                <label class="block text-center text-lg font-medium mb-4">Nhập mã OTP</label>
                <input type="text" 
                       name="otp" 
                       maxlength="6" 
                       required 
                       autofocus
                       inputmode="numeric"
                       {{-- ✅ FIX: Disable khi OTP hết hạn hoặc locked --}}
                       @if($otpExpired ?? false || $isLocked ?? false) disabled @endif
                       class="w-full text-center text-4xl tracking-widest px-4 py-4 border-2 border-purple-300 rounded-xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition text-purple-700 font-bold @error('otp') border-red-500 @enderror"
                       placeholder="------"
                       id="otpInput">
            </div>

            <button type="submit" 
                    id="submitBtn"
                    {{-- ✅ FIX: Disable nút khi OTP hết hạn hoặc locked --}}
                    @if($otpExpired ?? false || $isLocked ?? false) disabled @endif
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-4 rounded-xl hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                Xác nhận & Đăng nhập
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 mb-2">Không nhận được mã?</p>
            {{-- ✅ FIX: Thêm input hidden để truyền email + disable khi locked --}}
            <form action="{{ route('resend-otp') }}" method="POST" class="inline" id="resendForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') ?? $email ?? '' }}">
                <button type="submit" 
                        id="resendBtn" 
                        {{-- ✅ FIX: Tự động disable sau 30 giây --}}
                        class="text-purple-600 font-bold hover:underline disabled:text-gray-400 disabled:cursor-not-allowed transition" 
                        disabled>
                    Gửi lại mã OTP
                </button>
            </form>
        </div>

        {{-- ✅ FIX: Back to register link --}}
        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-800">
                ← Quay lại đăng ký
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = 5 * 60; // 5 phút = 300 giây
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        const otpInput = document.getElementById('otpInput');
        const submitBtn = document.getElementById('submitBtn');
        const otpForm = document.getElementById('otpForm');
        const resendForm = document.getElementById('resendForm');

        let timerInterval;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            // Định dạng MM:SS
            timerElement.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');

            // Đổi màu khi còn 1 phút
            if (timeLeft <= 60) {
                timerElement.classList.remove('text-purple-600');
                timerElement.classList.add('text-red-600');
            }

            // Hết hạn
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = '00:00';
                
                // ✅ FIX: Disable input và button khi OTP hết hạn
                otpInput.disabled = true;
                submitBtn.disabled = true;
                otpForm.style.opacity = '0.5';
                
                // Enable button gửi lại
                resendBtn.disabled = false;
                resendBtn.classList.remove('disabled:text-gray-400', 'disabled:cursor-not-allowed');
                resendBtn.classList.add('text-purple-600', 'hover:underline');
                
                // Hiển thị thông báo
                const warningMsg = document.createElement('div');
                warningMsg.className = 'mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm text-center font-medium';
                warningMsg.textContent = '⏰ Mã OTP đã hết hạn! Vui lòng gửi lại mã mới.';
                otpForm.insertAdjacentElement('afterend', warningMsg);
                
                return;
            }

            timeLeft--;
        }

        updateTimer(); // Gọi lần đầu để không trễ 1 giây
        timerInterval = setInterval(updateTimer, 1000);

        // ✅ FIX: Chỉ cho phép số trong input OTP
        otpInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // ✅ FIX: Auto-submit khi nhập đủ 6 chữ số
        otpInput.addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                // Optional: auto-submit
                // otpForm.submit();
            }
        });

        // ✅ FIX: Disable resend button trong 30 giây sau khi gửi
        resendForm.addEventListener('submit', function(e) {
            resendBtn.disabled = true;
            let resendCooldown = 30;
            const originalText = resendBtn.textContent;

            const cooldownInterval = setInterval(function() {
                resendBtn.textContent = 'Thử lại sau ' + resendCooldown + 's';
                resendCooldown--;

                if (resendCooldown < 0) {
                    clearInterval(cooldownInterval);
                    resendBtn.disabled = false;
                    resendBtn.textContent = originalText;
                }
            }, 1000);
        });

        // ✅ FIX: Clear interval khi page unload
        window.addEventListener('unload', () => {
            if (timerInterval) clearInterval(timerInterval);
        });
    });
</script>
@endsection