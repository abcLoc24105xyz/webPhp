{{-- resources/views/booking/confirm.blade.php --}}
@extends('layouts.app')
@section('title', 'Xác nhận đơn hàng')

@section('content')
<style>
    /* ============== PROGRESS HEADER SHRINK EFFECT ============== */
    .step-circle { transition: all 0.3s ease; }
    .step-circle.active { animation: pulse 2s infinite; }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 25px rgba(147, 51, 234, 0.6); }
        50% { box-shadow: 0 0 40px rgba(147, 51, 234, 0.9); }
    }
    .progress-bar {
        background: linear-gradient(90deg, #10b981, #14b8a6);
        transition: width 0.8s ease;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
    }

    /* Thu nhỏ khi cuộn */
    .progress-scrolled .header-content { padding: 0.75rem 0 !important; }
    .progress-scrolled .step-text { opacity: 0; height: 0; margin: 0 !important; overflow: hidden; }
    .progress-scrolled .step-circle-base { width: 3rem !important; height: 3rem !important; font-size: 1.25rem !important; }
    .progress-scrolled .step-circle-active { width: 3.5rem !important; height: 3.5rem !important; font-size: 1.75rem !important; }
    .progress-scrolled #steps-container { margin-bottom: 0.5rem !important; }
</style>

{{-- ==================== PROGRESS HEADER (FIXED & SHRINKABLE) ==================== --}}
<div id="progress-header" class="fixed top-20 left-0 right-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-white/10 shadow-2xl transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4">
        <div class="header-content py-5 transition-all duration-300">
            <div class="grid grid-cols-5 gap-3 text-center" id="steps-container">
                @for($i = 1; $i <= 3; $i++)
                    @php
                        $routes = [
                            1 => route('movie.detail', $show->movie->slug),
                            2 => route('seat.selection', $show->show_id),
                            3 => route('combo.select')
                        ];
                        $names = ['Chọn phim', 'Chọn ghế', 'Combo'];
                    @endphp
                    <a href="{{ $routes[$i] }}" class="group">
                        <div class="flex flex-col items-center">
                            <div class="step-circle-base w-12 h-12 rounded-full flex items-center justify-center bg-green-600 text-white shadow-lg">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                </svg>
                            </div>
                            <p class="step-text mt-2 text-xs font-bold text-gray-300 group-hover:text-white">{{ $names[$i-1] }}</p>
                        </div>
                    </a>
                @endfor

                <div class="group">
                    <div class="flex flex-col items-center">
                        <div class="step-circle step-circle-active w-14 h-14 rounded-full flex items-center justify-center text-3xl font-black bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-xl ring-4 ring-purple-400/50">
                            4
                        </div>
                        <p class="step-text mt-2 text-xs font-black text-white">Xác nhận</p>
                    </div>
                </div>

                <div class="group opacity-50">
                    <div class="flex flex-col items-center">
                        <div class="step-circle-base w-12 h-12 rounded-full flex items-center justify-center text-xl font-black bg-white/10 text-gray-500">
                            5
                        </div>
                        <p class="step-text mt-2 text-xs font-bold text-gray-500">Thanh toán</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="progress-bar h-full rounded-full" style="width: 80%"></div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MAIN CONTENT ==================== --}}
<div class="pt-48 pb-16 bg-gradient-to-br from-gray-900 via-slate-900 to-black min-h-screen">
    <div class="max-w-7xl mx-auto px-4">

        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500 mb-4">
                XÁC NHẬN ĐƠN HÀNG
            </h1>
            <p class="text-gray-300 text-lg">Vui lòng kiểm tra kỹ thông tin trước khi thanh toán</p>
        </div>

        <div class="grid lg:grid-cols-12 gap-8">

            {{-- CỘT TRÁI --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Thông tin suất chiếu --}}
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl">
                    <h2 class="text-2xl font-black text-white mb-8">Thông tin suất chiếu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/5 rounded-2xl p-6 text-center md:text-left">
                            <p class="text-gray-400 text-sm mb-2">Phim</p>
                            <p class="text-xl font-bold text-white">{{ $show->movie->title }}</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 text-center md:text-left">
                            <p class="text-gray-400 text-sm mb-2">Rạp</p>
                            <p class="text-xl font-bold text-white">{{ $show->cinema->cinema_name ?? $show->cinema->name }}</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 text-center md:text-left">
                            <p class="text-gray-400 text-sm mb-2">Phòng chiếu</p>
                            <p class="text-xl font-bold text-purple-400">{{ $show->room->room_name ?? 'Phòng VIP' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white/5 rounded-2xl p-6 text-center">
                            <p class="text-gray-400 text-sm mb-2">Ngày chiếu</p>
                            <p class="text-xl font-bold text-white">{{ \Carbon\Carbon::parse($show->show_date)->translatedFormat('d/m/Y') }}</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 text-center">
                            <p class="text-gray-400 text-sm mb-2">Giờ bắt đầu</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ substr($show->start_time, 0, 5) }}</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 text-center">
                            <p class="text-gray-400 text-sm mb-2">Giờ kết thúc</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ substr($show->end_time ?? $show->start_time, 0, 5) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Ghế đã chọn --}}
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl">
                    <h2 class="text-2xl font-black text-white mb-6">Ghế đã chọn</h2>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4">
                        @forelse($booking['seats'] ?? [] as $seat)
                            <div class="bg-gradient-to-br {{ $seat['type']==2 ? 'from-amber-500 to-orange-600' : ($seat['type']==3 ? 'from-pink-600 to-rose-700' : 'from-gray-600 to-gray-800') }} rounded-xl p-4 text-center shadow-lg hover:scale-105 transition">
                                <p class="text-2xl font-black text-white">{{ $seat['seat_num'] }}</p>
                                <p class="text-xs text-white/80 mt-1">{{ $seat['type']==2 ? 'VIP' : ($seat['type']==3 ? 'Ghế đôi' : 'Thường') }}</p>
                                <p class="text-sm font-bold text-yellow-300 mt-2">{{ number_format($seat['price']) }}đ</p>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500 py-8">Chưa chọn ghế</p>
                        @endforelse
                    </div>
                </div>

                {{-- Combo --}}
                @if(!empty($booking['combos']))
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl">
                        <h2 class="text-2xl font-black text-white mb-6">Combo đã chọn</h2>
                        <div class="space-y-4">
                            @foreach($booking['combos'] as $combo)
                                <div class="bg-white/5 rounded-2xl p-5 flex justify-between items-center">
                                    <div>
                                        <p class="text-xl font-bold text-white">{{ $combo['name'] }}</p>
                                        <p class="text-sm text-gray-400">Số lượng: {{ $combo['quantity'] }}</p>
                                    </div>
                                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($combo['total']) }}đ</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <a href="{{ route('combo.select') }}" class="block lg:hidden w-full bg-gray-800 hover:bg-gray-700 text-white text-center py-5 rounded-2xl font-black text-xl shadow-xl transition">
                    ← Quay lại chọn combo
                </a>
            </div>

            {{-- CỘT PHẢI - THANH TOÁN --}}
            <div class="lg:col-span-4">
                <div class="sticky top-36 space-y-6">

                    {{-- Mã giảm giá --}}
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-6 shadow-2xl">
                        <h3 class="text-xl font-black text-white mb-4">Mã giảm giá</h3>
                        <div id="promo-container">
                            @if(session('applied_promo'))
                                <div class="bg-green-600/30 border border-green-500 rounded-2xl p-5 text-center">
                                    <p class="text-2xl font-black text-white">{{ session('applied_promo') }}</p>
                                    <p class="text-green-300 mt-2">Đã áp dụng thành công!</p>
                                    <button type="button" onclick="removePromoCode()" class="mt-3 text-red-400 hover:text-red-300 font-bold underline">Bỏ mã</button>
                                </div>
                            @else
                                <form id="promoForm" class="space-y-4">
                                    @csrf
                                    <input type="text" id="promoCode" placeholder="Nhập mã giảm giá..." class="w-full bg-white/10 border border-white/30 rounded-xl px-5 py-4 text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 py-4 rounded-xl font-black text-white shadow-lg transition">
                                        Áp dụng mã
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Tổng kết --}}
                    <div class="bg-gradient-to-br from-purple-600/50 to-pink-600/50 backdrop-blur-xl rounded-3xl border border-purple-500/50 p-8 shadow-2xl">
                        <h3 class="text-2xl font-black text-white mb-6 text-center">Tổng kết đơn hàng</h3>
                        <div class="space-y-5 text-lg">
                            <div class="flex justify-between"><span class="text-gray-300">Tiền vé</span><span class="font-bold text-white">{{ number_format($booking['total']) }}đ</span></div>
                            @if($booking['combo_total'] ?? 0 > 0)
                                <div class="flex justify-between"><span class="text-gray-300">Combo</span><span class="font-bold text-green-400">{{ number_format($booking['combo_total']) }}đ</span></div>
                            @endif
                            @if(session('discount_amount'))
                                <div class="flex justify-between text-xl font-black text-green-400">
                                    <span>Giảm giá</span>
                                    <span>-{{ number_format(session('discount_amount')) }}đ</span>
                                </div>
                            @endif
                            <div class="border-t-2 border-white/30 pt-5">
                                <div class="flex justify-between text-2xl font-black">
                                    <span class="text-yellow-400">TỔNG CỘNG</span>
                                    <span class="text-yellow-400">{{ number_format($booking['grand_total']) }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-6 text-center shadow-2xl">
                        <h3 class="text-xl font-black text-white mb-4">Thanh toán qua</h3>
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl p-6">
                            <p class="text-2xl font-black text-white">MoMo / ATM</p>
                            <p class="text-gray-200 mt-2">An toàn • Nhanh chóng • Miễn phí</p>
                        </div>
                    </div>

                    {{-- NÚT THANH TOÁN SIÊU ĐẸP + LOADING MƯỢT --}}
                    <div class="space-y-4">
                        <button id="payNowBtn" type="button"
                                class="relative w-full overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-800 hover:from-indigo-700 hover:to-purple-900 text-white py-6 rounded-3xl font-black text-2xl shadow-2xl transition transform hover:scale-105 flex items-center justify-center gap-4">
                            <span id="payText">TIẾP TỤC THANH TOÁN</span>
                            <svg id="paySpinner" class="hidden w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <a href="{{ route('combo.select') }}" class="hidden lg:block w-full text-center bg-gray-800 hover:bg-gray-700 text-white py-5 rounded-2xl font-black text-lg shadow-xl transition">
                            ← Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ====================== JS ĐÃ FIX LOADING ĐẸP NHƯ CGV ====================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payNowBtn  = document.getElementById('payNowBtn');
    const payText    = document.getElementById('payText');
    const paySpinner = document.getElementById('paySpinner');

    // Thu nhỏ header khi cuộn
    const header = document.getElementById('progress-header');
    window.addEventListener('scroll', () => {
        header.classList.toggle('progress-scrolled', window.scrollY > 100);
    });

    // Áp dụng mã giảm giá (giữ nguyên)
    const promoForm = document.getElementById('promoForm');
    if (promoForm) {
        promoForm.addEventListener('submit', async e => {
            e.preventDefault();
            const code = document.getElementById('promoCode').value.trim();
            if (!code) return Swal.fire('Oops!', 'Vui lòng nhập mã giảm giá', 'warning');

            const res = await fetch("{{ route('booking.apply-promo') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ promo_code: code })
            });
            const data = await res.json();
            data.success
                ? Swal.fire('Thành công!', data.message || 'Áp dụng mã thành công!', 'success').then(() => location.reload())
                : Swal.fire('Thất bại', data.message || 'Mã không hợp lệ hoặc đã hết hạn', 'error');
        });
    }

    window.removePromoCode = async () => {
        const { isConfirmed } = await Swal.fire({
            title: 'Bỏ mã giảm giá?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Bỏ mã', cancelButtonText: 'Hủy'
        });
        if (isConfirmed) {
            await fetch("{{ route('booking.remove-promo') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            location.reload();
        }
    };

    // NÚT THANH TOÁN – HIỆN CHỮ + SPINNER ĐẸP
    payNowBtn.addEventListener('click', async () => {
        payNowBtn.disabled = true;
        payText.textContent = 'Đang chuyển hướng...';
        paySpinner.classList.remove('hidden');

        try {
            const res = await fetch("{{ route('momo.create') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await res.json();

            if (data.success && data.payUrl) {
                window.location.href = data.payUrl;
            } else if (data.zero_payment) {
                window.location.href = data.redirect_url;
            } else {
                Swal.fire('Lỗi', data.message || 'Thanh toán thất bại!', 'error');
                payText.textContent = 'TIẾP TỤC THANH TOÁN';
                paySpinner.classList.add('hidden');
                payNowBtn.disabled = false;
            }
        } catch (err) {
            Swal.fire('Lỗi kết nối', 'Không thể kết nối máy chủ', 'error');
            payText.textContent = 'TIẾP TỤC THANH TOÁN';
            paySpinner.classList.add('hidden');
            payNowBtn.disabled = false;
        }
    });
});
</script>
@endsection