@extends('layouts.app')
@section('title', 'Lịch sử đặt vé')

@section('content')
<div class="min-h-screen bg-gray-100 pt-6 pb-6"> 
    
    {{-- HEADER Tùy chỉnh (Giữ nguyên) --}}
    <div class="bg-gradient-to-br from-purple-600 to-pink-600 mb-8 pt-12 pb-8 shadow-md">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-white mb-0.5 tracking-tight">LỊCH SỬ ĐẶT VÉ</h1>
            <p class="text-base text-white/90">Theo dõi tất cả vé đã mua</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4">
        @if($bookings->isEmpty())
            <div class="bg-white rounded-lg p-8 text-center shadow-lg border-2 border-dashed border-gray-300"> 
                <div class="w-16 h-16 mx-auto bg-gray-200 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-lg text-gray-700 mb-4 font-semibold">Bạn chưa có vé nào trong lịch sử</p>
                <a href="{{ route('home') }}" 
                   class="inline-block bg-purple-600 text-white font-bold text-base px-6 py-2.5 rounded-full hover:bg-purple-700 shadow-sm transition">
                    Khám phá Phim Mới →
                </a>
            </div>
        @else
            <div class="space-y-5"> {{-- Khoảng cách giữa các thẻ vừa phải --}}
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-600 transition-all duration-300 hover:shadow-lg"> 
                        
                        {{-- BỐ CỤC 3 CỘT (Poster | Thông tin chi tiết | Tổng tiền & Nút) --}}
                        <div class="flex"> 
                            
                            {{-- Cột 1: Poster (Kích thước cố định, nhỏ vừa) --}}
                            <div class="flex-shrink-0 w-28 h-40 overflow-hidden rounded-l-lg"> 
                                <img src="{{ $booking->show->movie->poster ? asset('poster/' . basename($booking->show->movie->poster)) : asset('images/no-poster.jpg') }}" 
                                    alt="{{ $booking->show->movie->title }}"
                                    class="w-full h-full object-cover">
                            </div>

                            {{-- Cột 2: Tiêu đề, Rạp & Thông tin cốt lõi --}}
                            <div class="flex-1 p-4 flex flex-col justify-between"> 
                                
                                {{-- Tiêu đề Phim và Rạp --}}
                                <div class="pb-2 mb-2 border-b border-gray-100">
                                    <h3 class="text-lg font-black text-gray-900 leading-tight mb-0.5">{{ $booking->show->movie->title }}</h3>
                                    <p class="text-sm font-semibold text-purple-600">{{ $booking->show->cinema->cinema_name }}</p>
                                    <p class="text-xs font-light uppercase tracking-widest text-gray-500 mt-1">Mã: {{ $booking->booking_code }}</p>
                                </div>

                                {{-- Thông tin Ngày, Giờ, Ghế (Bố cục 3 hàng gọn gàng) --}}
                                <div class="grid grid-cols-3 gap-3 text-center text-xs">
                                    
                                    <div class="p-1 rounded-md">
                                        <p class="text-gray-500 mb-0.5 font-medium">NGÀY</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $booking->show->show_date->translatedFormat('d/m/y') }}</p>
                                    </div>
                                    
                                    <div class="bg-purple-50 rounded-md p-1"> 
                                        <p class="text-purple-700 mb-0.5 font-medium">GIỜ BẮT ĐẦU</p>
                                        <p class="text-xl font-black text-pink-600 leading-none">
                                            {{ \Carbon\Carbon::parse($booking->show->start_time)->format('H:i') }}
                                        </p>
                                    </div>
                                    
                                    <div class="p-1 rounded-md">
                                        <p class="text-gray-500 mb-0.5 font-medium">GHẾ / PHÒNG</p>
                                        <p class="text-sm font-bold text-gray-800 leading-none">
                                            {{ $booking->seats->count() }} Ghế
                                        </p>
                                        <p class="text-xs font-medium text-gray-600 mt-0.5">
                                            Phòng: {{ $booking->show->room_code }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Cột 3: Tổng tiền & Nút hành động (Flex-end) --}}
                            <div class="w-56 p-4 flex flex-col justify-end items-end border-l border-gray-100"> 
                                
                                {{-- Tổng tiền --}}
                                <div class="text-right mb-4 w-full">
                                    <p class="text-xs font-bold text-gray-600 mb-1">TỔNG THANH TOÁN</p>
                                    <div class="inline-block bg-green-100 rounded-md px-3 py-1.5"> 
                                        <p class="text-xl font-black text-green-700 leading-none"> 
                                            {{ number_format($booking->total_amount) }}đ
                                        </p>
                                    </div>
                                </div>

                                {{-- Nút --}}
                                <a href="{{ route('profile.ticket.detail', $booking->booking_code) }}"
                                   class="inline-block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-sm px-4 py-2 rounded-full hover:shadow-lg transition duration-300"> 
                                    Xem Chi Tiết & QR Code →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $bookings->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection