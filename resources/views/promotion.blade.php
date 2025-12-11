{{-- resources/views/promotion.blade.php --}}
@extends('layouts.app')

@section('title', 'Ưu đãi đặc biệt')

@section('content')
{{-- Giữ nguyên nền tối và padding tổng thể --}}
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-black text-white py-12 md:py-16 px-4">

    <div class="max-w-6xl mx-auto text-center">
        <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-500 mb-4 drop-shadow-xl">
            ƯU ĐÃI ĐẶC BIỆT
        </h1>
        <p class="text-lg md:text-xl text-gray-300 mb-10 md:mb-12">Chỉ dành riêng cho bạn – Đặt ngay kẻo hết!</p>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

        <div class="relative overflow-hidden rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 p-6 md:p-8 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/20">
            {{-- Giảm font size cho nội dung chính --}}
            <div class="text-5xl md:text-6xl font-black text-yellow-400 mb-3">50%</div>
            <h3 class="text-2xl md:text-3xl font-bold mb-2">THỨ 3 VUI VẺ</h3>
            <p class="text-base md:text-lg text-gray-300">Giảm 50% giá vé 2D tất cả các suất chiếu trong ngày thứ 3 hàng tuần</p>
            <div class="mt-4 inline-block px-5 py-2 bg-yellow-500 text-black font-bold rounded-full text-xs">Áp dụng toàn hệ thống</div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 p-6 md:p-8 text-center hover:bg-white/10 hover:border-pink-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-pink-500/20">
            {{-- Giảm font size cho nội dung chính --}}
            <div class="text-4xl md:text-5xl font-black text-pink-400 mb-3 leading-tight">MUA 2 TẶNG 1</div>
            <h3 class="text-2xl md:text-3xl font-bold mb-2">COMBO COUPLE</h3>
            <p class="text-base md:text-lg text-gray-300">Mua 2 combo bất kỳ → Tặng ngay 1 bắp rang bơ lớn</p>
            <div class="mt-4 inline-block px-5 py-2 bg-pink-500 text-white font-bold rounded-full text-xs">Chỉ áp dụng đến hết tháng này</div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 p-6 md:p-8 text-center hover:bg-white/10 hover:border-orange-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-orange-500/20 md:col-span-2">
            {{-- Giảm font size cho nội dung chính --}}
            <div class="text-5xl md:text-6xl font-black text-orange-400 mb-3">100K</div>
            <h3 class="text-2xl md:text-3xl font-bold mb-2">TÂN BINH VIP</h3>
            <p class="text-base md:text-lg text-gray-300">Đăng ký thành viên mới → Nhận ngay voucher 100.000đ cho lần đặt vé đầu tiên</p>
            <div class="mt-4 inline-block px-5 py-2 bg-orange-500 text-black font-bold rounded-full text-xs">Chỉ 100 suất đầu tiên</div>
        </div>

    </div>

    <div class="text-center mt-12 md:mt-16">
        <a href="{{ route('movies.all', ['tab' => 'showing']) }}"
           class="inline-block px-10 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-black font-black text-xl md:text-2xl rounded-full hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/50">
            ĐẶT VÉ NGAY
        </a>
    </div>

</div>
@endsection