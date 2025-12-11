{{-- resources/views/booking/seat-selection.blade.php --}}
@extends('layouts.app')

@section('title', 'Chọn ghế - ' . $show->movie->title)

@section('content')
@php
    use Carbon\Carbon;
    $endTime = Carbon::parse($show->end_time)->format('H:i'); 
@endphp

<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>

<script>
    let recaptchaToken = '';

    // Hàm lấy token mới – gọi khi cần
    function refreshRecaptchaToken() {
        return new Promise((resolve) => {
            grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {action: 'hold_seats'})
                .then(token => {
                    recaptchaToken = token;
                    console.log('reCAPTCHA token đã sẵn sàng');
                    resolve(token);
                })
                .catch(() => resolve(''));
        });
    }

    // Lấy token ngay khi trang load
    grecaptcha.ready(() => {
        refreshRecaptchaToken();
    });

    // Tự động refresh mỗi 90 giây (token sống ~2 phút)
    setInterval(refreshRecaptchaToken, 90000);
</script>

<style>
    .step-circle {
        transition: all 0.3s ease;
    }

    .step-circle.active {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 25px rgba(147, 51, 234, 0.6); }
        50% { box-shadow: 0 0 40px rgba(147, 51, 234, 0.9); }
    }

    .progress-bar {
        background: linear-gradient(90deg, #9333ea, #ec4899);
        transition: width 0.8s ease;
        box-shadow: 0 0 20px rgba(147, 51, 234, 0.6);
    }

    .glass-effect {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    /* CSS MỚI CHO HIỆU ỨNG THU NHỎ KHI CUỘN */
    .step-text {
        transition: all 0.3s ease;
    }
    
    /* Ẩn chữ/giảm padding cho thanh tiến trình thu nhỏ */
    .progress-scrolled #progress-content-wrapper {
        padding-top: 0.75rem !important; /* py-3 */
        padding-bottom: 0.75rem !important; /* py-3 */
    }

    /* Ẩn chữ (tên các bước) */
    .progress-scrolled .step-text {
        opacity: 0;
        height: 0;
        margin-top: 0 !important;
        overflow: hidden;
    }

    /* Giảm kích thước vòng tròn bước thông thường */
    .progress-scrolled .step-circle-base {
        width: 3rem !important; /* w-12 */
        height: 3rem !important; /* h-12 */
        font-size: 1.25rem !important; /* text-xl */
    }

    /* Giảm kích thước vòng tròn bước đang active */
    .progress-scrolled .step-circle-active {
        width: 3.5rem !important; /* w-14 */
        height: 3.5rem !important; /* h-14 */
        font-size: 1.75rem !important; /* text-2xl */
    }

    /* Giảm margin giữa các bước */
    .progress-scrolled #steps-container {
        margin-bottom: 0.5rem !important; /* mb-2 */
    }

    .cinema-screen {
        width: 80%;
        max-width: 600px;
        height: 25px;
        background: linear-gradient(to bottom, #777, #333);
        margin: 10px auto 40px auto; /* Tăng khoảng cách dưới */
        border-radius: 50% / 100% 100% 0 0; /* Tạo hình cong phía trên */
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5), 0 -10px 30px rgba(147, 51, 234, 0.4); /* Thêm bóng */
        transform: perspective(800px) rotateX(10deg); /* Hiệu ứng 3D */
        border: 2px solid #555;
    }
</style>

{{-- ==================== BOOKING INFO STICKY HEADER (CHỈ CÓ PROGRESS BAR) ==================== --}}
{{-- ==================== THANH TIẾN TRÌNH 5 BƯỚC (FIXED & SHRINKABLE) ==================== --}}
{{-- top-20 để nằm ngay dưới Navbar (giả sử Navbar là h-20) --}}
<div id="progress-header" class="fixed top-20 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-md border-b border-white/10 transition-all duration-300 ease-in-out transform">
    <div class="max-w-7xl mx-auto px-4 py-4 md:py-6 transition-all duration-300 ease-in-out" id="progress-content-wrapper">
        <div class="grid grid-cols-5 gap-2 md:gap-3 text-center mb-4 transition-all duration-300 ease-in-out" id="steps-container">
            
            <a href="{{ route('movie.detail', $show->movie->slug) }}" class="group hover:opacity-100 opacity-80 transition">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-xl md:text-2xl font-black bg-green-600 text-white shadow-lg shadow-green-500/50 group-hover:bg-green-700">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-300 step-text">Chọn rạp & suất chiếu</p>
                </div>
            </a>

            <div class="group">
                <div class="flex flex-col items-center">
                    <div class="step-circle active step-circle-active w-14 md:w-16 h-14 md:h-16 rounded-full flex items-center justify-center text-2xl md:text-3xl font-black bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-xl ring-3 ring-purple-400/50">
                        2
                    </div>
                    <p class="mt-1 text-xs md:text-sm font-black text-white step-text">Chọn ghế</p>
                </div>
            </div>

            <div class="group opacity-60">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-xl md:text-2xl font-black bg-white/10 text-gray-500">
                        3
                    </div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-500 step-text">Combo</p>
                </div>
            </div>

            <div class="group opacity-40">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-lg md:text-xl font-black bg-white/5 text-gray-600">
                        4
                    </div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-600 step-text">Xác nhận</p>
                </div>
            </div>

            <div class="group opacity-30">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-lg md:text-xl font-black bg-white/5 text-gray-700">
                        5
                    </div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-700 step-text">Thanh toán</p>
                </div>
            </div>
        </div>

        <div class="relative h-1.5 bg-white/10 rounded-full overflow-hidden">
            <div class="progress-bar h-full rounded-full" style="width: 40%"></div>
        </div>
    </div>
</div>
        
        
{{-- ==================== KẾT THÚC BOOKING INFO HEADER ==================== --}}


<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black py-4 px-4" style="padding-top: 10rem;">
    <div class="max-w-7xl mx-auto">
        
        {{-- Cấu trúc 3 cột: Chi tiết (1) | Bản đồ ghế (2) | Thông tin đặt vé (1) --}}
        <div class="grid lg:grid-cols-4 gap-8">
            
            {{-- ==================== CỘT TRÁI: THÔNG TIN CHI TIẾT PHIM/SUẤT CHIẾU (lg:col-span-1) ==================== --}}
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-2xl shadow-2xl p-6 h-fit sticky top-[6.5rem]">
                    <img src="{{ (isset($show->movie->poster) && $show->movie->poster) ? asset('poster/' . $show->movie->poster) : asset('images/movie-placeholder.jpg') }}" 
                          alt="{{ $show->movie->title }}" 
                          class="rounded-xl shadow-xl w-full aspect-[2/3] object-cover mb-6">
                    
                    <h3 class="text-2xl font-black text-white mb-4">{{ $show->movie->title }}</h3>
                    
                    <div class="space-y-3 text-sm font-medium text-gray-300 border-t border-white/10 pt-4">
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 font-bold w-16">Rạp:</span>
                            <span class="font-semibold">{{ $show->cinema->cinema_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 font-bold w-16">Phòng:</span>
                            <span class="font-semibold">{{ $show->room->room_name ?? $show->room_code }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 font-bold w-16">Ngày:</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($show->show_date)->translatedFormat('l, d/m/Y') }}</span>
                        </div>
                        {{-- THÔNG TIN GIỜ BẮT ĐẦU --}}
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 font-bold w-16">Bắt đầu:</span>
                            <span class="text-yellow-400 font-black text-lg">{{ substr($show->start_time, 0, 5) }}</span>
                        </div>
                        {{-- THÔNG TIN GIỜ KẾT THÚC (MỚI) --}}
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 font-bold w-16">Kết thúc:</span>
                            <span class="text-yellow-400 font-black text-lg">{{ $endTime }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== CỘT GIỮA: BẢN ĐỒ GHẾ (lg:col-span-2) ==================== --}}
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden">
                    
                    {{-- MÀN HÌNH CHIẾU MỚI (ĐÃ ĐẢO NGƯỢC THỨ TỰ) --}}
                    <div class="pt-8 pb-1 text-center">
                        <p class="text-gray-400 text-sm mb-8">Vui lòng chọn ghế bạn muốn</p> 
                        <h2 class="text-xl font-black tracking-widest text-white/90 drop-shadow-lg">MÀN HÌNH</h2> 
                        <div class="cinema-screen"></div> 
                    </div>
                    <div class="p-4 md:p-8">
                        <div class="space-y-3">
                            @php
                                $seatsByRow = $show->room->seats
                                    ->sortBy('seat_num')
                                    ->groupBy(fn($s) => substr($s->seat_num, 0, 1));
                            @endphp

                            @foreach($seatsByRow as $rowLetter => $seatsInRow)
                                <div class="flex justify-center">
                                    <div class="flex items-center gap-1 md:gap-2">
                                        <div class="text-lg font-black text-purple-400 w-5 md:w-7 text-right">{{ $rowLetter }}</div>

                                        <div class="grid grid-cols-10 gap-1.5 md:gap-2">
                                            @for($i = 1; $i <= 10; $i++)
                                                @php
                                                    $seatNum = $rowLetter . $i;
                                                    $seat = $seatsInRow->firstWhere('seat_num', $seatNum);
                                                    $isUnavailable = $seat && $unavailableSeats->contains($seat->seat_id);
                                                    $isMine = $seat && auth()->check() && 
                                                        \App\Models\SeatHold::where('seat_id', $seat->seat_id)
                                                             ->where('show_id', $show->show_id)
                                                             ->where('user_id', auth()->id())
                                                             ->where('expires_at', '>', now())
                                                             ->exists();
                                                    $type = $seat?->seat_type ?? 1;
                                                @endphp

                                                @if($seat)
                                                    <button 
                                                        type="button"
                                                        data-seat-id="{{ $seat->seat_id }}"
                                                        data-seat-num="{{ $seat->seat_num }}"
                                                        data-price="{{ $seat->default_price }}"
                                                        data-type="{{ $type }}"
                                                        class="seat w-7 h-7 md:w-8 md:h-8 rounded-lg font-bold text-xs flex items-center justify-center shadow-md
                                                             {{ $isUnavailable && !$isMine ? 'bg-red-600/80 text-white cursor-not-allowed opacity-70' : '' }}
                                                             {{ $isMine ? 'bg-green-500 ring-4 ring-green-400 text-white shadow-lg animate-pulse' : '' }}
                                                             {{ !$isUnavailable && !$isMine 
                                                                 ? ($type == 2 ? 'bg-gradient-to-br from-amber-400 to-orange-500 text-black hover:shadow-lg' 
                                                                     : ($type == 3 ? 'bg-gradient-to-r from-rose-500 to-pink-600 text-white hover:shadow-lg' 
                                                                         : 'bg-gray-300 hover:bg-yellow-400 text-gray-800'))
                                                                 : '' }}"
                                                        @disabled($isUnavailable && !$isMine)>
                                                        @if($isUnavailable && !$isMine)
                                                            <span>✕</span>
                                                        @elseif($type == 3)
                                                            <span class="text-[0.6rem]">👥</span>
                                                        @elseif($type == 2)
                                                            <span class="text-[0.6rem]">⭐</span>
                                                        @else
                                                            {{ $i }}
                                                        @endif
                                                    </button>
                                                @else
                                                    <div class="w-7 h-7 md:w-8 md:h-8"></div>
                                                @endif
                                            @endfor
                                        </div>

                                        <div class="text-lg font-black text-purple-400 w-5 md:w-7 text-left">{{ $rowLetter }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Legend --}}
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-4 md:gap-6 mt-10 pt-6 border-t border-white/10">
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-gray-300 rounded-lg mb-2"></div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">Thường</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg mb-2 flex items-center justify-center text-xs">⭐</div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">VIP</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-gradient-to-r from-rose-500 to-pink-600 rounded-lg mb-2 flex items-center justify-center text-xs">👥</div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">Ghế đôi</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-yellow-400 rounded-lg mb-2"></div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">Chọn</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-green-500 ring-4 ring-green-400 rounded-lg mb-2"></div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">Của bạn</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 bg-red-600/80 rounded-lg mb-2 flex items-center justify-center text-xs">✕</div>
                                <span class="text-xs md:text-sm font-bold text-gray-300">Đã bán</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== CỘT PHẢI: SIDEBAR THÔNG TIN ĐƠNHÀNG (STICKY - lg:col-span-1) ==================== --}}
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-2xl shadow-2xl p-6 sticky top-[6.5rem] h-fit"> 
                    <h3 class="text-xl font-black text-white text-center mb-6">Thông tin đặt vé</h3>

                    <div id="selected-info" class="space-y-2 mb-6 min-h-40 max-h-64 overflow-y-auto bg-white/5 rounded-xl p-4 border border-white/10">
                        <p class="text-gray-400 text-center text-sm">Chưa chọn ghế nào</p>
                    </div>

                    <div class="border-t border-white/20 pt-6 mb-6">
                        <p class="text-gray-300 text-sm mb-2">Tổng tiền</p>
                        <p id="total-price" class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">0đ</p>
                    </div>

                    <button id="proceed-btn" disabled
                            class="w-full btn-proceed bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 px-4 rounded-xl font-black text-base md:text-lg shadow-lg hover:shadow-xl transition transform hover:scale-[1.01] disabled:opacity-50 disabled:scale-100 flex items-center justify-center gap-2 uppercase tracking-wide">
                        <span>Tiếp tục</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    {{-- ĐÃ SỬA: Thêm ID để JS kiểm soát --}}
                    <p class="text-xs text-gray-500 text-center mt-4">⏱️ Ghế sẽ được giữ <span id="hold-countdown" class="font-black text-yellow-400">10:00</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const COMBO_ROUTE = "{{ route('combo.select') }}";
    let selectedSeats = [];
    let total = 0;

    // ==================== LOGIC GIỮ GHẾ VÀ GIỚI HẠN VÉ (MỚI) ====================
    const MAX_TICKETS = 8; // Giới hạn tối đa 8 vé
    const HOLD_DURATION = 10 * 60; // 10 phút (tính bằng giây)
    let timeRemaining = HOLD_DURATION;
    let countdownInterval;

    // Tính tổng số lượng vé thực tế (Ghế đôi = 2 vé)
    function calculateTotalTickets(currentSeats = selectedSeats) {
        let count = 0;
        currentSeats.forEach(id => {
            const btn = document.querySelector(`[data-seat-id="${id}"]`);
            if (btn) {
                const type = parseInt(btn.dataset.type);
                count += (type === 3 ? 2 : 1); // Ghế đôi (type 3) tính là 2 vé
            }
        });
        return count;
    }

    // Xử lý hủy ghế (nhấn nút X)
    function handleCancelSeat() {
        const seatId = this.dataset.seatId;
        const btn = document.querySelector(`[data-seat-id="${seatId}"]`);

        if (btn && btn.classList.contains('selected')) {
            // Thực hiện thao tác HỦY chọn (giống như click lần 2)
            btn.classList.remove('bg-yellow-400', 'selected', 'text-gray-900', 'pulse-btn');
            
            const type = btn.dataset.type;
            if (type == 2) btn.classList.add('bg-gradient-to-br', 'from-amber-400', 'to-orange-500', 'text-black');
            else if (type == 3) btn.classList.add('bg-gradient-to-r', 'from-rose-500', 'to-pink-600', 'text-white');
            else btn.classList.add('bg-gray-300', 'hover:bg-yellow-400', 'text-gray-800');
        }
        
        selectedSeats = selectedSeats.filter(id => id !== seatId);
        updateOrderSummary();
    }

    // Bắt đầu/Đặt lại bộ đếm ngược 10 phút
    function resetAndStartCountdown() {
        clearInterval(countdownInterval);
        const timerElement = document.getElementById('hold-countdown');
        if (!timerElement) return;

        if (selectedSeats.length > 0) {
            // Chỉ reset lại nếu thời gian còn lại đã hết hoặc gần hết, hoặc khi list ghế vừa thay đổi
            // Trong trường hợp này, ta reset lại 10 phút mỗi khi có sự thay đổi
            timeRemaining = HOLD_DURATION;
            
            countdownInterval = setInterval(() => {
                timeRemaining--;

                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timerElement.classList.add('animate-pulse');

                if (timeRemaining <= 60) {
                     timerElement.classList.add('text-red-400'); // Cảnh báo đỏ khi còn 1 phút
                } else {
                     timerElement.classList.remove('text-red-400');
                }

                if (timeRemaining <= 0) {
                    clearInterval(countdownInterval);
                    timerElement.textContent = 'Hết hạn';
                    document.getElementById('proceed-btn').disabled = true;
                    // Tự động load lại trang để cập nhật ghế đã hết hạn giữ (tùy chọn)
                    // alert('Thời gian giữ ghế đã hết. Vui lòng chọn lại.');
                    // location.reload();
                }
            }, 1000);
        } else {
            timerElement.textContent = '10:00';
            timerElement.classList.remove('animate-pulse', 'text-red-400');
        }
    }


    // HÀM CẬP NHẬT TÓM TẮT ĐƠN HÀNG (ĐÃ THÊM NÚT HỦY VÀ GỌI TIMER)
    function updateOrderSummary() {
        let html = '';
        total = 0;
        selectedSeats.forEach(id => {
            const btn = document.querySelector(`[data-seat-id="${id}"]`);
            if (!btn) return; 
            const price = parseInt(btn.dataset.price);
            total += price;
            const type = btn.dataset.type == 2 ? 'VIP' : (btn.dataset.type == 3 ? 'GHẾ ĐÔI' : 'THƯỜNG');
            
            // THÊM NÚT HỦY (X)
            html += `<div class="order-item bg-yellow-400/20 border border-yellow-400/40 px-4 py-3 rounded-lg text-sm font-bold text-yellow-300 flex justify-between items-center jump-in">
                        <p>Ghế ${btn.dataset.seatNum} (${type}) - ${price.toLocaleString()}đ</p>
                        <button type="button" data-seat-id="${id}" class="cancel-seat-btn text-red-400 hover:text-red-500 transition ml-4 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>`;
        });
        document.getElementById('selected-info').innerHTML = html || '<p class="text-gray-400 text-center text-sm">Chưa chọn ghế nào</p>';
        document.getElementById('total-price').textContent = total.toLocaleString() + 'đ';
        document.getElementById('proceed-btn').disabled = selectedSeats.length === 0;
        
        // Gắn lại sự kiện cho các nút hủy mới
        document.querySelectorAll('.cancel-seat-btn').forEach(btn => {
            btn.removeEventListener('click', handleCancelSeat); // Tránh gắn nhiều lần
            btn.addEventListener('click', handleCancelSeat);
        });

        // Cập nhật và reset Timer
        resetAndStartCountdown();
    }

    // Xử lý chọn ghế (ĐÃ BỔ SUNG LOGIC GIỚI HẠN VÉ)
    document.querySelectorAll('.seat:not([disabled])').forEach(btn => {
        btn.addEventListener('click', function () {
            const seatId = this.dataset.seatId;
            const seatType = parseInt(this.dataset.type);
            const ticketsToAdd = seatType === 3 ? 2 : 1;
            let currentTicketCount = calculateTotalTickets(selectedSeats);

            if (this.classList.contains('bg-yellow-400')) {
                // Hủy chọn
                selectedSeats = selectedSeats.filter(id => id !== seatId);
                this.classList.remove('bg-yellow-400', 'selected', 'text-gray-900', 'pulse-btn');
                
                const type = this.dataset.type;
                if (type == 2) this.classList.add('bg-gradient-to-br', 'from-amber-400', 'to-orange-500', 'text-black');
                else if (type == 3) this.classList.add('bg-gradient-to-r', 'from-rose-500', 'to-pink-600', 'text-white');
                else this.classList.add('bg-gray-300', 'hover:bg-yellow-400', 'text-gray-800');

            } else {
                // Chọn
                if (currentTicketCount + ticketsToAdd > MAX_TICKETS) {
                    alert(`Bạn chỉ có thể chọn tối đa ${MAX_TICKETS} vé. Vui lòng hủy chọn ghế khác.`);
                    return; 
                }

                this.classList.remove('bg-gray-300', 'bg-gradient-to-br', 'bg-gradient-to-r', 'text-black', 'text-white', 'text-gray-800', 'hover:bg-yellow-400');
                this.classList.add('bg-yellow-400', 'text-gray-900', 'selected', 'pulse-btn');
                selectedSeats.push(seatId);
            }
            updateOrderSummary();
        });
    });
    
    // NÚT TIẾP TỤC – ĐÃ SỬA LỖI VÒNG LOADING VÀ BỔ SUNG RECAPTCHA
    document.getElementById('proceed-btn').addEventListener('click', async function () {
        if (selectedSeats.length === 0) return;

        this.disabled = true;
        
        // CHỈ SVG QUAY, CHỮ ĐỨNG YÊN
        const originalContent = this.innerHTML; // Lưu nội dung ban đầu
        this.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg> Đang xử lý...`;
        
        // LẤY TOKEN MỚI NGAY TRƯỚC KHI GỬI
        await refreshRecaptchaToken();

        fetch("{{ route('seat.hold', $show->show_id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                seats: selectedSeats,
                'g-recaptcha-response': recaptchaToken
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Ngừng đếm ngược khi chuyển trang thành công
                clearInterval(countdownInterval);
                window.location.href = COMBO_ROUTE;
            } else {
                alert(data.message || 'Ghế đã bị đặt! Vui lòng chọn lại.');
                location.reload(); // Reload để lấy trạng thái ghế mới nhất
            }
        })
        .catch(() => {
            alert('Lỗi mạng! Vui lòng kiểm tra kết nối.');
            this.disabled = false;
            // Trả về nội dung nút ban đầu
            this.innerHTML = originalContent;
        });
    });

    // ==================== LOGIC SCROLL (JQUERY) ====================
    $(document).ready(function() {
        const $header = $('#booking-info-header');
        
        // Áp dụng class 'scrolled' khi cuộn qua 100px
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
        }).trigger('scroll');

        // Khởi tạo trạng thái ban đầu: kiểm tra nếu có ghế đang giữ (từ load) thì update/start timer.
        // Tuy nhiên, trong context này, chúng ta chỉ gọi updateOrderSummary() để khởi tạo nút và giao diện.
        updateOrderSummary();
    });

    // ==================== LOGIC THU NHỎ THANH PROGRESS KHI CUỘN ====================
    const progressHeader = document.getElementById('progress-header');
    const scrollThreshold = 100; 

    function handleScroll() {
        if (window.scrollY > scrollThreshold) {
            progressHeader.classList.add('progress-scrolled');
        } else {
            progressHeader.classList.remove('progress-scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Kiểm tra ngay khi tải trang

</script>
@endsection