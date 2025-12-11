@extends('layouts.app')
@section('title', 'Phim Nổi Bật')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-slate-900 to-black py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 mb-4">
                PHIM NỔI BẬT
            </h1>
            <p class="text-xl text-purple-200">Những bộ phim đang làm mưa làm gió tại rạp!</p>
        </div>

        @if($featuredMovies->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @foreach($featuredMovies as $movie)
                    <a href="{{ route('movie.detail', $movie->slug) }}" 
                       class="group block transform transition-all duration-300 hover:scale-105 hover:-translate-y-2">
                        <div class="relative overflow-hidden rounded-2xl shadow-2xl bg-gray-900 border border-purple-500/30">
                            @if($movie->early_premiere_date && \Carbon\Carbon::parse($movie->early_premiere_date)->gte(now()))
                                <div class="absolute top-2 left-2 z-10 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-black animate-pulse">
                                    SUẤT SỚM
                                </div>
                            @endif
                            <img src="{{ $movie->poster ? asset('poster/' . basename($movie->poster)) : 'https://via.placeholder.com/400x600/1a0033/ffffff?text=' . urlencode($movie->title) }}"
                                 alt="{{ $movie->title }}"
                                 class="w-full h-64 md:h-80 object-cover group-hover:brightness-75 transition">
                            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black to-transparent">
                                <h3 class="text-white font-bold text-sm md:text-base line-clamp-2 leading-tight">
                                    {{ $movie->title }}
                                </h3>
                                @if($movie->age_limit)
                                    <span class="inline-block mt-2 px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">
                                        {{ $movie->age_limit }}+ 
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-2xl text-purple-300">Hiện chưa có phim nổi bật nào.</p>
            </div>
        @endif
    </div>
</div>
@endsection