@extends('layouts.app')
@section('title', 'Vé xem phim - ' . ($booking->booking_code ?? 'N/A'))

@section('content')
<div class="min-h-screen bg-gray-100 py-12 sm:py-10">
    <div class="max-w-4xl mx-auto px-4">
        
        {{-- CONTAINER CHÍNH CỦA VÉ --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-gray-200">
            
            {{-- HEADER: LOGO, TÊN THƯƠNG HIỆU VÀ MÃ ĐẶT VÉ --}}
            <div class="bg-gradient-to-r from-purple-700 to-pink-700 text-white pt-10 pb-6 text-center">
                <h1 class="text-3xl font-bold mb-2">VÉ ĐIỆN TỬ ĐÃ XÁC NHẬN</h1>
                <p class="text-xl text-white opacity-90">Mã đơn hàng: <span class="font-mono font-extrabold text-2xl tracking-wider inline-block bg-white bg-opacity-20 px-3 rounded-md">{{ $booking->booking_code ?? 'N/A' }}</span></p>
            </div>

            @if(!$booking->show || !$booking->show->movie)
                {{-- Xử lý lỗi --}}
                <div class="text-center py-20">
                    <p class="text-3xl text-red-600 font-bold">Không tìm thấy thông tin suất chiếu hoặc phim!</p>
                    <a href="{{ route('profile.history') }}" class="mt-6 inline-block bg-purple-600 text-white px-12 py-4 rounded-full text-xl hover:bg-purple-700 transition">
                        ← Quay lại lịch sử
                    </a>
                </div>
            @else
                
                {{-- NỘI DUNG VÉ (GRID LAYOUT) --}}
                <div class="grid lg:grid-cols-3 divide-x-4 divide-dashed divide-gray-200">
                    
                    {{-- CỘT 1: THÔNG TIN PHIM VÀ SUẤT CHIẾU (2/3 CHIỀU RỘNG) --}}
                    <div class="lg:col-span-2 p-8 lg:p-10">
                        
                        {{-- THÔNG TIN PHIM VÀ RẠP --}}
                        <div class="flex items-start space-x-6 pb-6 border-b border-gray-200 mb-6">
                            <img src="{{ $booking->show->movie->poster 
                                ? asset('poster/' . basename($booking->show->movie->poster)) 
                                : asset('images/no-poster.jpg') }}" 
                                alt="{{ $booking->show->movie->title ?? 'Phim không xác định' }}"
                                class="w-20 h-32 object-cover rounded-lg shadow-md flex-shrink-0"> {{-- Giảm kích thước Poster --}}
                            
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">RẠP PHIM</p>
                                <p class="text-2xl font-semibold text-purple-600 mb-3">
                                    {{ $booking->show->cinema->cinema_name ?? 'Rạp không xác định' }}
                                </p>
                                <h2 class="text-4xl font-extrabold text-gray-900 leading-tight"> {{-- Tăng kích thước Tiêu đề Phim --}}
                                    {{ $booking->show->movie->title ?? 'Không có tên phim' }}
                                </h2>
                            </div>
                        </div>

                        {{-- THÔNG TIN NGÀY GIỜ VÀ PHÒNG --}}
                        <div class="grid grid-cols-3 gap-6 mb-8 text-center">
                            <div>
                                <p class="text-lg text-gray-600 font-medium mb-1">Ngày chiếu</p>
                                <p class="text-xl font-bold text-gray-800">
                                    {{ $booking->show->show_date?->translatedFormat('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="bg-pink-100 rounded-lg p-3"> {{-- Nền nổi bật Giờ chiếu --}}
                                <p class="text-lg text-pink-700 font-medium mb-1">Giờ chiếu</p>
                                <p class="text-4xl font-black text-pink-600">
                                    {{ $booking->show->start_time 
                                        ? \Carbon\Carbon::parse($booking->show->start_time)->format('H:i')
                                        : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-lg text-gray-600 font-medium mb-1">Phòng</p>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ $booking->show->room_code ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        {{-- GHẾ NGỒI --}}
                        <div class="bg-purple-50 rounded-xl p-5 border-2 border-purple-300">
                            <p class="text-xl text-purple-700 font-bold mb-4 border-b border-purple-200 pb-2">Ghế ngồi ({{ $booking->seats->count() }} Ghế)</p>
                            <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-7 lg:grid-cols-8 gap-3 justify-center"> {{-- Sắp xếp thành lưới đều --}}
                                @if($booking->seats->count())
                                    @foreach($booking->seats as $seat)
                                        <span class="bg-purple-600 text-white px-3 py-1 rounded-md text-xl font-extrabold text-center shadow-md">
                                            {{ $seat->seat_num ?? '?' }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500 italic col-span-full text-center">Chưa có ghế nào được chọn</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- COMBO --}}
                        @if($booking->combos->count())
                            <div class="mt-6 bg-pink-50 rounded-xl p-5 border-2 border-pink-300">
                                <p class="text-xl text-pink-700 font-bold mb-4 border-b border-pink-200 pb-2">Combo Đã Chọn</p>
                                <div class="space-y-3">
                                    @foreach($booking->combos as $combo)
                                        <div class="flex justify-between text-lg text-gray-700">
                                            <span>{{ $combo->combo_name }} (x{{ $combo->pivot->quantity }})</span>
                                            <span class="font-bold text-pink-600">{{ number_format($combo->pivot->combo_price * $combo->pivot->quantity) }}đ</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- CỘT 2: QR CODE VÀ TỔNG TIỀN (1/3 CHIỀU RỘNG) --}}
                    <div class="lg:col-span-1 p-8 lg:p-10 text-center flex flex-col justify-between items-center">
                        
                        {{-- QR CODE --}}
                        <div class="mb-8 w-full">
                            <p class="text-xl font-bold text-purple-700 mb-4">Mã QR Check-in</p>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($booking->booking_code) }}" 
                                alt="QR Code vé" class="mx-auto border-4 border-gray-100 rounded-xl max-w-[220px]">
                            <p class="text-sm text-gray-600 mt-4 italic">Quét mã này tại quầy để nhận vé giấy</p>
                        </div>
                        
                        {{-- TỔNG TIỀN --}}
                        <div class="p-5 bg-green-100 rounded-xl border-4 border-green-500 text-center shadow-lg w-full">
                            <span class="text-x0.2 font-bold text-gray-700 block mb-1">TỔNG TIỀN</span>
                            <span class="text-2xl font-black text-green-700 block leading-tight"> {{-- Tăng kích thước Tổng tiền --}}
                                {{ number_format($booking->total_amount ?? 0) }}đ
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- FOOTER HÀNH ĐỘNG VÀ LƯU Ý --}}
                <div class="p-8 lg:p-10 border-t-4 border-dashed border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button onclick="window.print()"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-xl py-4 rounded-xl hover:shadow-xl transition transform hover:scale-[1.02]">
                            <i class="fas fa-print mr-2"></i> IN VÉ NGAY
                        </button>
                        <a href="{{ route('profile.history') }}"
                           class="flex-1 bg-gray-200 text-gray-800 font-bold text-xl py-4 rounded-xl text-center hover:bg-gray-300 transition">
                            ← Quay lại lịch sử
                        </a>
                    </div>
                    
                    {{-- Lưu ý --}}
                    <div class="mt-4 bg-yellow-50 p-5 rounded-xl border border-yellow-300 text-sm text-gray-700">
                        <p class="font-bold text-lg mb-2 text-yellow-800">Lưu ý quan trọng:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Vui lòng có mặt tại rạp ít nhất <strong>15 phút</strong> trước giờ chiếu để đổi vé.</li>
                            <li>Mã QR chỉ có hiệu lực một lần.</li>
                            <li>Giữ lại thông tin này để đối chiếu khi cần.</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Điều chỉnh cho chế độ in */
@media print {
    body * { visibility: hidden; }
    .bg-white, .bg-white *, .bg-gray-100 { visibility: visible; }
    .bg-white { 
        position: absolute; 
        left: 0; 
        top: 0; 
        width: 100%; 
        box-shadow: none !important; 
        border: none !important;
        background: white !important; 
    }
    button, a { display: none !important; }

    /* Đảm bảo màu sắc hiển thị trong chế độ in (cho header) */
    .bg-gradient-to-r { 
        background: #a855f7 !important; 
        -webkit-print-color-adjust: exact; 
        color-adjust: exact; 
        color: white !important;
    }
    /* Đảm bảo các màu nền nổi bật vẫn hiển thị (hoặc dùng màu đậm) */
    .bg-pink-100 { background: #fce7f3 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
    .bg-green-100 { background: #d1fae5 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
    .bg-purple-50 { background: #f9f5ff !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
    .bg-yellow-50 { background: #fffdf2 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }

    .text-green-700 { color: #059669 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
    .bg-purple-600 { background: #8b5cf6 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }


    /* Ẩn đường phân chia ngang */
    .divide-dashed { border-style: solid !important; border-color: transparent !important; }
    
    /* Đảm bảo bố cục 2 cột vẫn hợp lý khi in */
    .lg\:grid-cols-3 { grid-template-columns: 2fr 1fr !important; }
    .lg\:col-span-1, .lg\:col-span-2 { max-width: 100%; }
}
</style>
@endsection