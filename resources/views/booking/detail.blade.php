{{-- resources/views/booking/detail.blade.php --}}
@extends('layouts.app')

@section('title', 'Vé điện tử - ' . $reservation->booking_code)

@section('content')
<style>
    /* CSS Animations & Custom Styles (Giữ nguyên) */
    .step-circle { transition: all 0.3s ease; }
    .step-circle.completed { animation: scaleComplete 0.6s ease; }
    @keyframes scaleComplete { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
    .progress-bar {
        background: linear-gradient(90deg, #10b981, #14b8a6);
        transition: width 0.8s ease;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
    }
    .glass-effect {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    .ticket-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(30, 41, 59, 0.7));
        border: 2px solid rgba(148, 163, 184, 0.3);
    }
    .success-animation { animation: bounceIn 0.8s ease; }
    @keyframes bounceIn { 0% { transform: scale(0.8); opacity: 0; } 50% { transform: scale(1.05); } 100% { transform: scale(1); opacity: 1; } }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black py-8 px-4">
    <div class="max-w-5xl mx-auto">

        {{-- ==================== 1. THANH TIẾN TRÌNH & THÔNG BÁO THÀNH CÔNG ==================== --}}
        <div class="mb-12">
            {{-- Thanh tiến trình --}}
            <div class="grid grid-cols-5 gap-3 md:gap-4 text-center">
                @php
                    $steps = ['Chọn rạp & suất chiếu', 'Chọn ghế', 'Combo', 'Xác nhận', 'Hoàn tất'];
                @endphp
                @foreach($steps as $i => $name)
                    @php $step_num = $i + 1; @endphp
                    <div class="group">
                        <div class="flex flex-col items-center">
                            @if($step_num < 5)
                                <div class="step-circle completed w-14 md:w-16 h-14 md:h-16 rounded-full flex items-center justify-center text-xl md:text-2xl font-black bg-green-600 text-white shadow-lg">
                                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @elseif($step_num == 5)
                                <div class="step-circle completed success-animation w-16 md:w-20 h-16 md:h-20 rounded-full flex items-center justify-center text-3xl md:text-4xl font-black bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-2xl ring-4 ring-green-400/50">
                                    <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                                    </svg>
                                </div>
                            @endif
                            <p class="mt-2 text-xs md:text-sm font-bold {{ $step_num == 5 ? 'text-white' : 'text-gray-300' }}">
                                {{ $name }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="relative mt-6 h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="progress-bar h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
        
        <div class="text-center mb-12 success-animation">
            <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-400 mb-4">
                ĐẶT VÉ THÀNH CÔNG!
            </h1>
        </div>

        {{-- ==================== 2. MÃ CHECK-IN NỔI BẬT (KEY FOCUS) ==================== --}}
        <div class="text-center mb-12">
             <div class="glass-effect rounded-3xl inline-block px-10 py-5 mt-4 border-2 border-green-400/50 shadow-xl">
                <p class="text-gray-300 text-sm md:text-base mb-3">MÃ CHECK-IN (Sử dụng tại quầy)</p>
                <p class="text-4xl md:text-5xl font-black text-yellow-400 tracking-wider">{{ $reservation->booking_code }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-10">
            
            {{-- ==================== CỘT TRÁI (LỚN): THẺ VÉ ĐIỆN TỬ (E-TICKET CARD) ==================== --}}
            <div class="lg:col-span-8">
                <div class="ticket-card rounded-3xl shadow-2xl overflow-hidden">
                    
                    {{-- Header: Phim và Rạp --}}
                    <div class="bg-gradient-to-r from-purple-600/80 to-pink-600/80 backdrop-blur p-6 md:p-10 text-center">
                        <h2 class="text-3xl md:text-4xl font-black text-white mb-2">{{ $reservation->show->movie->title }}</h2>
                        <p class="text-lg md:text-xl text-white/80 break-words">{{ $reservation->show->cinema->cinema_name ?? 'Rạp không xác định' }}</p>
                    </div>

                    <div class="p-6 md:p-8 text-white space-y-8">
                        
                        {{-- Thông tin suất chiếu (3 cột) --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="glass-effect rounded-xl p-4 text-center">
                                <p class="text-gray-300 text-xs mb-2">Phòng chiếu</p>
                                <p class="text-lg font-black text-purple-400">{{ $reservation->show->room->room_name ?? 'Phòng VIP' }}</p>
                            </div>
                            <div class="glass-effect rounded-xl p-4 text-center">
                                <p class="text-gray-300 text-xs mb-2">Ngày chiếu</p>
                                <p class="text-lg font-black">
                                    {{ \Carbon\Carbon::parse($reservation->show->show_date)->translatedFormat('d/m/Y') }}
                                </p>
                            </div>
                            <div class="glass-effect rounded-xl p-4 text-center">
                                <p class="text-gray-300 text-xs mb-2">Giờ chiếu</p>
                                <p class="text-lg font-black text-yellow-400">
                                    {{ substr($reservation->show->start_time, 0, 5) }}
                                </p>
                            </div>
                        </div>

                        {{-- Ghế đã chọn --}}
                        <div class="glass-effect rounded-xl p-6">
                            <div class="flex justify-between items-center mb-5">
                                <p class="text-gray-300 text-sm font-bold">Ghế đã chọn</p>
                                <p class="text-yellow-400 font-black text-md">{{ $reservation->seats->count() }} ghế</p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-3">
                                @forelse($reservation->seats as $seat)
                                    @php
                                        $color = $seat->seat_type_id == 2 ? 'bg-amber-400 text-gray-900' : ($seat->seat_type_id == 3 ? 'bg-pink-500 text-white' : 'bg-yellow-400 text-gray-900');
                                    @endphp
                                    <div class="px-5 py-2 rounded-full text-md font-black shadow-lg {{ $color }}">
                                        {{ $seat->seat_num }}
                                    </div>
                                @empty
                                    <p class="text-gray-500">Không có ghế</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Combo đã chọn --}}
                        @if($reservation->combos->count() > 0)
                            <div class="glass-effect rounded-xl p-6">
                                <p class="text-yellow-400 text-sm font-bold mb-4">Combo đã chọn</p>
                                <div class="space-y-3">
                                    @foreach($reservation->combos as $c)
                                        <div class="flex justify-between items-center bg-white/5 rounded-lg px-4 py-3 border border-white/10">
                                            <p class="text-white font-bold">{{ $c->combo_name }}</p>
                                            <span class="bg-purple-600/60 text-white px-4 py-1 rounded-full text-sm font-bold">
                                                × {{ $c->pivot->quantity }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ==================== CỘT PHẢI (NHỎ): TỔNG KẾT & HƯỚNG DẪN ==================== --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- Tổng tiền và Tình trạng thanh toán --}}
                <div class="glass-effect rounded-3xl p-6 md:p-8 text-center border-2 border-white/20">
                    <p class="text-gray-300 text-lg mb-3">Tổng số tiền đã thanh toán</p>
                    <p class="text-4xl font-black text-yellow-400 mb-5">
                        {{ number_format($reservation->total_amount) }}đ
                    </p>
                    <div class="inline-block bg-green-500/20 border border-green-500/50 rounded-full px-6 py-2">
                        <p class="text-green-400 text-sm font-bold">
                            @if($reservation->total_amount == 0)
                                Đã thanh toán (MIỄN PHÍ)
                            @else
                                Đã thanh toán thành công
                            @endif
                        </p>
                    </div>
                </div>
                
                {{-- HƯỚNG DẪN CHI TIẾT --}}
                <div class="bg-gradient-to-br from-purple-900/50 to-pink-900/50 backdrop-blur-xl p-6 rounded-3xl border border-white/20 shadow-2xl">
                    <h3 class="text-xl font-black text-white mb-4 text-center">Hướng dẫn Check-in</h3>
                    <div class="text-gray-200 text-sm space-y-3">
                        <p class="flex items-start gap-2">
                            <span class="text-yellow-400 font-bold text-lg">1.</span>
                            Vé điện tử (Mã QR) đã được gửi tới email của bạn. Vui lòng kiểm tra hộp thư.
                        </p>
                        <p class="flex items-start gap-2">
                            <span class="text-yellow-400 font-bold text-lg">2.</span>
                            Xuất trình Mã Check-in hoặc QR code trong email tại quầy để nhận vé giấy.
                        </p>
                        <p class="flex items-start gap-2">
                            <span class="text-yellow-400 font-bold text-lg">3.</span>
                            Vui lòng có mặt trước giờ chiếu ít nhất 15 phút để có trải nghiệm tốt nhất.
                        </p>
                    </div>
                    {{-- DÒNG CHÚC MỪNG ĐÃ ĐƯỢC THÊM --}}
                    <p class="text-center text-lg font-bold text-green-400 mt-6">
                        Chúc quý khách xem phim vui vẻ!
                    </p>
                </div>
                
                {{-- NÚT HÀNH ĐỘNG --}}
                <div class="space-y-4 pt-2">
                    <a href="{{ route('home') }}" 
                       class="w-full block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 rounded-xl text-lg font-bold transition transform hover:scale-[1.02] shadow-lg text-center">
                        Về trang chủ
                    </a>
                    <a href="{{ route('profile.history') }}" 
                       class="w-full block glass-effect hover:bg-white/15 text-white py-4 rounded-xl text-lg font-bold transition transform hover:scale-[1.02] text-center border border-white/30">
                        Xem Lịch sử đặt vé
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection