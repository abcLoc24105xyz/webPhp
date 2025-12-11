{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

{{-- ==================== 1. HERO SECTION ====================--}}
<section class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline 
               class="w-full h-full object-cover scale-105"
               poster="{{ asset('images/cinema-fallback.jpg') }}">
            <source src="{{ asset('videos/test.mp4') }}" type="video/mp4">
            <source src="{{ asset('videos/cinema-bg.webm') }}" type="video/webm">
            <img src="{{ asset('images/cinema-fallback.jpg') }}" alt="Cinema Background" class="w-full h-full object-cover">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-gray-50"></div>
        <div class="absolute inset-0 bg-purple-900/20 mix-blend-overlay"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center mt-[-80px]">
        <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-purple-300 text-sm font-bold tracking-wider mb-6 animate-pulse">
            TRẢI NGHIỆM ĐIỆN ẢNH ĐỊNH CAO
        </span>
        
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-heading font-extrabold text-white mb-6 leading-tight drop-shadow-2xl">
            Thế Giới Điện Ảnh <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
                Trong Tầm Tay
            </span>
        </h1>
        
        <p class="text-lg md:text-2xl text-gray-200 mb-10 font-light max-w-3xl mx-auto leading-relaxed">
            Đặt vé nhanh chóng, thanh toán tiện lợi, ưu đãi ngập tràn tại hơn 50 rạp chiếu trên toàn quốc.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
            <a href="#phim-dang-chieu" 
               class="group relative px-8 py-4 bg-white text-gray-900 font-bold rounded-full overflow-hidden shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(168,85,247,0.6)] transition-all duration-300">
                <span class="relative z-10 group-hover:text-purple-600 transition-colors">Khám phá ngay</span>
            </a>
            
            <a href="{{ route('login') }}" 
               class="px-8 py-4 rounded-full border border-white/30 bg-white/5 backdrop-blur-sm text-white font-bold hover:bg-white/10 hover:border-white transition-all duration-300">
                Đặt vé ngay
            </a>
        </div>
    </div>

    {{-- Scroll Indicator với animation mũi tên --}}
    <a href="#phim-dang-chieu" class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 group cursor-pointer">
        <div class="flex flex-col items-center gap-2 animate-bounce">
            <svg class="w-6 h-6 text-white group-hover:text-purple-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
            <span class="text-white text-xs font-semibold tracking-widest uppercase opacity-70 group-hover:opacity-100 transition-opacity">Scroll</span>
        </div>
    </a>
</section>

{{-- ==================== 2. PHIM ĐANG CHIẾU (FEATURED) ====================--}}
<section id="phim-dang-chieu" class="pb-24 pt-4 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h2 class="text-4xl font-heading font-extrabold text-gray-900 mb-4">
                Phim Nổi Bật
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-pink-600 mx-auto rounded-full"></div>
            <p class="text-gray-500 mt-4 text-lg">
                Đừng bỏ lỡ những siêu phẩm điện ảnh đang làm mưa làm gió
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
            @forelse($featuredMovies as $movie)
                <div class="group relative h-full transform transition-all duration-300 hover:scale-105">
                    <a href="{{ route('movie.detail', $movie->slug) }}" class="block h-full">
                        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gray-200 aspect-[2/3]">
                            @php $filename = $movie->poster ? basename($movie->poster) : null; @endphp
                            
                            @if($movie->age_limit)
                                <div class="absolute top-3 left-3 z-20">
                                    <span class="px-2.5 py-1 bg-gray-900/80 backdrop-blur-md text-white text-xs font-bold rounded-lg border border-white/20 shadow-lg">
                                        {{ $movie->age_limit }}+
                                    </span>
                                </div>
                            @endif

                            <img 
                                src="{{ $filename && file_exists(public_path('poster/'.$filename)) ? asset('poster/'.$filename) : 'https://via.placeholder.com/400x600/e2e8f0/94a3b8?text=' . urlencode($movie->title) }}"
                                alt="{{ $movie->title }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <button class="w-full py-3 bg-purple-600 text-white font-bold rounded-xl shadow-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                        Đặt vé
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-1">
                            <h3 class="text-lg font-heading font-bold text-gray-900 line-clamp-1 group-hover:text-purple-600 transition-colors" title="{{ $movie->title }}">
                                {{ $movie->title }}
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 font-medium gap-2">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600 text-xs">{{ $movie->category->name ?? 'Phim lẻ' }}</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $movie->duration }}'
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-500">Không có phim đang chiếu</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('movies.all') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-white border border-gray-200 shadow-sm text-gray-700 font-bold hover:text-purple-600 hover:border-purple-600 hover:shadow-md transition-all duration-300 group">
                Xem tất cả phim đang chiếu
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ==================== 3. PHIM SẮP CHIẾU (DARK MODE) ====================--}}
<section id="phim-sap-chieu" class="py-24 bg-slate-900 relative overflow-hidden">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-heading font-extrabold text-white mb-4">
                Sắp Khởi Chiếu
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-purple-500 to-pink-500 mx-auto rounded-full"></div>
            <p class="text-slate-400 mt-4 text-lg">Những siêu phẩm điện ảnh sắp đổ bộ</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
            @foreach($upcomingMovies as $movie)
                @php
                    $releaseDate = null;
                    if ($movie->release_date) {
                        try {
                            $cleanDate = preg_replace('/\s+.*/', '', trim($movie->release_date));
                            $releaseDate = \Carbon\Carbon::parse($cleanDate);
                        } catch (\Exception $e) {}
                    }
                @endphp

                <a href="{{ route('movie.detail', $movie->slug) }}" class="group block h-full transform transition-all duration-300 hover:scale-105">
                    <div class="relative overflow-hidden rounded-2xl bg-slate-800 aspect-[2/3] border border-slate-700 group-hover:border-purple-500/50 transition-colors">
                        @php $filename = $movie->poster ? basename($movie->poster) : null; @endphp
                        
                        <img 
                            src="{{ $filename && file_exists(public_path('poster/'.$filename)) ? asset('poster/'.$filename) : 'https://via.placeholder.com/400x600/1e293b/94a3b8?text=' . urlencode($movie->title) }}"
                            alt="{{ $movie->title }}"
                            class="w-full h-full object-cover filter brightness-75 group-hover:brightness-100 group-hover:scale-105 transition-all duration-500 ease-out">

                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black via-black/80 to-transparent">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-purple-400 font-bold text-sm tracking-wide">
                                    {{ $releaseDate ? $releaseDate->translatedFormat('d/m/Y') : 'Coming Soon' }}
                                </span>
                                @if($movie->age_limit)
                                    <span class="px-2 py-0.5 rounded border border-white/30 text-white text-[10px] font-bold">
                                        {{ $movie->age_limit }}+
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-white font-bold text-lg leading-tight line-clamp-1 group-hover:text-purple-300 transition-colors">
                                {{ $movie->title }}
                            </h4>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('movies.all', ['tab' => 'upcoming']) }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-purple-600/20 border border-purple-500/30 backdrop-blur-sm text-white font-bold hover:bg-purple-600/30 hover:border-purple-500/50 transition-all duration-300 group">
                Xem tất cả phim sắp chiếu
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection