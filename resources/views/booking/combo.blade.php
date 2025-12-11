@extends('layouts.app')

@section('title', 'Chọn Combo - ' . $show->movie->title)

@section('content')

{{-- ==================== CSS ==================== --}}
<style>
    /* STEP & PROGRESS */
    .step-circle { transition: all 0.3s ease; }
    .step-circle.active { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{box-shadow:0 0 25px rgba(147,51,234,.6);} 50%{box-shadow:0 0 40px rgba(147,51,234,.9);} }
    .progress-bar { background: linear-gradient(90deg,#9333ea,#ec4899); transition: width .8s ease; box-shadow:0 0 20px rgba(147,51,234,.6); }
    .glass-effect { background: rgba(15,23,42,.8); backdrop-filter: blur(10px); border:1px solid rgba(148,163,184,.2); }
    .combo-item { transition: all .3s cubic-bezier(.34,1.56,.64,1); }
    .combo-item:hover { transform: translateY(-4px); }
    @keyframes jumpUp { from {opacity:0; transform:translateY(15px) scale(.9);} to {opacity:1; transform:translateY(0) scale(1);} }
    .jump-in { animation: jumpUp .4s ease-out forwards; }
    .pulse-btn { animation: pulseScale .3s ease-in-out; }
    @keyframes pulseScale {0%,100%{transform:scale(1);}50%{transform:scale(1.15);}}

    /* PROGRESS SHRINK ON SCROLL */
    .step-text { transition: all .3s ease; }
    .progress-scrolled #progress-content-wrapper { padding-top:.75rem !important; padding-bottom:.75rem !important; }
    .progress-scrolled .step-text { opacity:0; height:0; margin-top:0 !important; overflow:hidden; }
    .progress-scrolled .step-circle-base { width:3rem !important; height:3rem !important; font-size:1.25rem !important; }
    .progress-scrolled .step-circle-active { width:3.5rem !important; height:3.5rem !important; font-size:1.75rem !important; }
    .progress-scrolled #steps-container { margin-bottom:.5rem !important; }
</style>

{{-- ==================== HEADER PROGRESS 5 BƯỚC ==================== --}}
<div id="progress-header" class="fixed top-20 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-md border-b border-white/10 transition-all duration-300 ease-in-out transform">
    <div class="max-w-7xl mx-auto px-4 py-4 md:py-6 transition-all duration-300 ease-in-out" id="progress-content-wrapper">
        <div class="grid grid-cols-5 gap-2 md:gap-3 text-center mb-4 transition-all duration-300 ease-in-out" id="steps-container">
            {{-- Step 1 --}}
            <a href="{{ route('movie.detail', $show->movie->slug) }}" class="group">
                <div class="flex flex-col items-center">
                    <div class="step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-xl md:text-2xl font-black bg-green-600 text-white shadow-lg shadow-green-500/50">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <p class="step-text mt-1 text-xs md:text-sm font-bold text-gray-300 group-hover:text-white transition">Chọn rạp & suất chiếu</p>
                </div>
            </a>
            {{-- Step 2 --}}
            <a href="{{ route('seat.selection', $show->show_id) }}" class="group">
                <div class="flex flex-col items-center">
                    <div class="step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-xl md:text-2xl font-black bg-green-600 text-white shadow-lg shadow-green-500/50">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <p class="step-text mt-1 text-xs md:text-sm font-bold text-gray-300 group-hover:text-white transition">Chọn ghế</p>
                </div>
            </a>
            {{-- Step 3 --}}
            <div class="group">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-active active w-14 md:w-16 h-14 md:h-16 rounded-full flex items-center justify-center text-2xl md:text-3xl font-black bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-xl ring-3 ring-purple-400/50">3</div>
                    <p class="mt-1 text-xs md:text-sm font-black text-white step-text">Combo</p>
                </div>
            </div>
            {{-- Step 4 --}}
            <div class="group opacity-60">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-lg md:text-xl font-black bg-white/10 text-gray-500">4</div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-500 step-text">Xác nhận</p>
                </div>
            </div>
            {{-- Step 5 --}}
            <div class="group opacity-40">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-circle-base w-12 md:w-14 h-12 md:h-14 rounded-full flex items-center justify-center text-lg md:text-xl font-black bg-white/5 text-gray-600">5</div>
                    <p class="mt-1 text-xs md:text-sm font-bold text-gray-600 step-text">Thanh toán</p>
                </div>
            </div>
        </div>
        <div class="relative h-1.5 bg-white/10 rounded-full overflow-hidden">
            <div class="progress-bar h-full rounded-full" style="width:60%"></div>
        </div>
    </div>
</div>

{{-- ==================== HIDDEN DATA ==================== --}}
<div id="booking-data"
     data-ticket-total="{{ $booking['total'] ?? 0 }}"
     data-seat-count="{{ count($booking['seats'] ?? []) }}"
     style="display:none;"></div>

{{-- ==================== MAIN CONTENT ==================== --}}
<div class="pt-40 pb-8 bg-gradient-to-br from-slate-800 to-black min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8">

            {{-- ==================== CỘT 1: THÔNG TIN PHIM & GHẾ ==================== --}}
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-xl shadow-lg p-4 md:p-5 border border-white/20 sticky top-40 h-fit">
                    <h3 class="text-xl font-black text-white mb-3">{{ $show->movie->title }}</h3>
                    <div class="space-y-2 text-sm text-gray-300">
                        <div class="flex items-center"><svg class="w-4 h-4 mr-2 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span class="font-bold text-white">{{ $show->cinema->cinema_name}}</span>
                        </div>
                        <div class="flex items-center"><svg class="w-4 h-4 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm1 14h-2V7h2v9z"/></svg>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($show->show_date)->translatedFormat('d/m/Y') }}</span>
                            <span class="mx-2 text-gray-500">|</span>
                            <span class="font-black text-yellow-400">{{ substr($show->start_time,0,5) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <h4 class="text-sm font-black text-purple-400 mb-2 flex items-center gap-1">
                            Ghế đã chọn:
                            <span class="text-white text-base" id="seat-count-display">{{ count($booking['seats'] ?? []) }} ghế</span>
                        </h4>
                        <div class="flex flex-wrap gap-1.5 max-h-40 overflow-y-auto pr-1">
                            @forelse($booking['seats'] ?? [] as $seat)
                                @php
                                    $typeName = $seat['type']==2?'VIP':($seat['type']==3?'Đôi':'Thường');
                                    $bgColor = $seat['type']==2?'from-amber-400 to-orange-500':($seat['type']==3?'from-rose-500 to-pink-600':'from-gray-300 to-gray-400');
                                    $textColor = $seat['type']==3?'text-white':'text-gray-900';
                                @endphp
                                <div class="bg-gradient-to-br {{ $bgColor }} rounded px-2 py-1 font-bold text-xs shadow-sm">
                                    <span class="{{ $textColor }}">{{ $seat['seat_num'] }}</span>
                                </div>
                            @empty
                                <p class="text-gray-400 italic text-xs">Chưa chọn ghế nào</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== CỘT 2: COMBO ==================== --}}
            <div class="lg:col-span-2">
                <h2 class="text-2xl md:text-3xl font-black text-white mb-6 text-center">🥤🍟 Chọn Combo Bắp Nước</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    @forelse($combos as $combo)
                        <div class="combo-item glass-effect rounded-xl shadow-lg overflow-hidden hover:border-purple-500/50 border border-white/20">
                            @if($combo->image)
                                <img src="{{ asset('images/combos/'.$combo->image) }}" alt="{{ $combo->combo_name }}" class="w-full h-32 md:h-40 object-cover object-center">
                            @else
                                <div class="bg-gradient-to-br from-orange-500 to-pink-600 h-32 md:h-40 flex items-center justify-center">
                                    <span class="text-white font-black text-lg md:text-xl text-center px-4">{{ $combo->combo_name }}</span>
                                </div>
                            @endif
                            <div class="p-4 md:p-5">
                                <h3 class="text-sm md:text-base font-black text-white mb-1">{{ $combo->combo_name }}</h3>
                                <p class="text-xs text-gray-300 mb-3 line-clamp-2">{{ $combo->description }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="text-lg md:text-xl font-black text-yellow-400">{{ number_format($combo->price) }}đ</div>
                                    <div class="flex items-center gap-1.5 md:gap-2">
                                        <button type="button" class="minus-btn w-6 h-6 md:w-7 md:h-7 bg-red-500 hover:bg-red-600 text-white rounded-full font-bold shadow-lg transition transform hover:scale-110 text-sm" data-id="{{ $combo->combo_id }}">−</button>
                                        <input type="number" value="0" min="0" readonly class="qty-input w-8 md:w-10 text-center text-sm md:text-base font-black bg-white/10 border border-purple-400/50 rounded-lg py-1 text-white" data-id="{{ $combo->combo_id }}" data-price="{{ $combo->price }}" data-name="{{ $combo->combo_name }}">
                                        <button type="button" class="plus-btn w-6 h-6 md:w-7 md:h-7 bg-green-500 hover:bg-green-600 text-white rounded-full font-bold shadow-lg transition transform hover:scale-110 text-sm" data-id="{{ $combo->combo_id }}">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 md:py-12 text-gray-400 text-base md:text-lg">
                            Hiện tại chưa có combo nào
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ==================== CỘT 3: ĐƠN HÀNG ==================== --}}
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-xl shadow-lg p-5 md:p-6 sticky top-40 h-fit border border-white/20">
                    <h3 class="text-lg md:text-xl font-black text-white mb-5 text-center border-b border-white/10 pb-3">🧾 Tóm Tắt Đơn Hàng</h3>
                    <form id="comboForm" action="{{ route('combo.store') }}" method="POST" class="space-y-4">
                        @csrf
                        {{-- Vé xem phim --}}
                        <div class="bg-gradient-to-br from-purple-600/40 to-pink-600/40 rounded-lg p-3 md:p-4 border border-purple-400/50">
                            <div class="flex justify-between text-sm md:text-base font-bold text-white mb-1">
                                <span>Vé xem phim</span>
                                <span id="ticket-total" class="font-black text-yellow-300">0đ</span>
                            </div>
                            <p class="text-xs text-gray-300">Số lượng: <span id="seat-count-text">0</span> ghế</p>
                        </div>
                        {{-- Combo đã chọn --}}
                        <div id="selected-combos" class="space-y-2 max-h-56 overflow-y-auto">
                            <p class="text-center text-gray-400 py-6 text-xs md:text-sm">Chưa chọn combo nào</p>
                        </div>
                        {{-- Tổng tiền combo --}}
                        <div class="border-t border-white/20 pt-3">
                            <div class="flex justify-between text-sm md:text-base font-bold text-white">
                                <span>Tiền combo</span>
                                <span id="combo-total" class="text-green-400 font-black">0đ</span>
                            </div>
                        </div>
                        {{-- Tổng cộng (ĐÃ CHUYỂN THÀNH 2 DÒNG) --}}
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg p-4 md:p-5 mt-5">
                            {{-- Đã thay đổi flex justify-between thành text-center --}}
                            <div class="text-center font-black text-white">
                                {{-- Dòng 1: TỔNG TIỀN (cỡ chữ nhỏ hơn) --}}
                                <div class="text-base md:text-lg mb-1">TỔNG TIỀN</div>
                                {{-- Dòng 2: Số tiền (cỡ chữ lớn hơn, đã giảm so với ban đầu) --}}
                                <div id="grand-total" class="text-2xl md:text-3xl">0đ</div>
                            </div>
                        </div>
                        {{-- Giảm cỡ chữ nút Tiếp tục --}}
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white py-3 md:py-4 rounded-lg font-bold text-xs md:text-sm shadow-lg transition transform hover:scale-[1.01] uppercase tracking-wide">
                            → TIẾP TỤC
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ==================== JS ==================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dataEl = document.getElementById('booking-data');
    const ticketTotal = parseInt(dataEl.dataset.ticketTotal) || 0;
    const seatCount = parseInt(dataEl.dataset.seatCount) || 0;

    // Init
    document.getElementById('ticket-total').textContent = ticketTotal.toLocaleString() + 'đ';
    // Đã thay đổi cỡ chữ ở đây để hiển thị lớn hơn theo cấu trúc 2 dòng mới
    document.getElementById('grand-total').textContent = ticketTotal.toLocaleString() + 'đ'; 
    document.getElementById('seat-count-text').textContent = seatCount;
    document.getElementById('seat-count-display').textContent = seatCount + ' ghế';

    // +/- buttons
    document.querySelectorAll('.plus-btn, .minus-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const input = document.querySelector(`input.qty-input[data-id="${id}"]`);
            let qty = parseInt(input.value) || 0;
            qty = this.classList.contains('plus-btn') ? qty+1 : Math.max(qty-1,0);
            input.value = qty;
            this.classList.add('pulse-btn');
            setTimeout(()=>this.classList.remove('pulse-btn'),300);
            updateOrder();
        });
    });

    function updateOrder(){
        let comboTotal=0, html='';
        document.querySelectorAll('input.qty-input').forEach(input=>{
            const qty = parseInt(input.value) || 0;
            if(qty>0){
                const price = parseInt(input.dataset.price);
                const name = input.dataset.name;
                const total = price*qty;
                comboTotal += total;
                html += `<div class="bg-white/10 rounded-lg p-3 border border-purple-400/50 jump-in">
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex-1">
                                    <p class="font-bold text-sm text-white">${name}</p>
                                    <p class="text-xs text-gray-300">${qty} × ${price.toLocaleString()}đ</p>
                                </div>
                                <p class="font-black text-yellow-400 text-sm">${total.toLocaleString()}đ</p>
                            </div>
                        </div>`;
            }
        });
        if(!html) html = '<p class="text-center text-gray-400 py-6 text-xs md:text-sm">Chưa chọn combo nào</p>';
        document.getElementById('selected-combos').innerHTML = html;
        document.getElementById('combo-total').textContent = comboTotal.toLocaleString()+'đ';
        document.getElementById('grand-total').textContent = (ticketTotal+comboTotal).toLocaleString()+'đ';

        const comboForm=document.getElementById('comboForm');
        document.querySelectorAll('input.qty-input').forEach(input=>{
            const id=input.dataset.id, qty=input.value;
            let hidden=document.querySelector(`input[name="combos[${id}]"]`);
            if(qty>0){
                if(!hidden){
                    hidden=document.createElement('input');
                    hidden.type='hidden';
                    hidden.name=`combos[${id}]`;
                    comboForm.appendChild(hidden);
                }
                hidden.value=qty;
            }else if(hidden){
                comboForm.removeChild(hidden);
            }
        });
    }

    // Scroll shrink effect
    const progressHeader=document.getElementById('progress-header');
    const scrollThreshold=100;
    function handleScroll(){
        if(window.scrollY>scrollThreshold) progressHeader.classList.add('progress-scrolled');
        else progressHeader.classList.remove('progress-scrolled');
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    updateOrder();
});
</script>

@endsection