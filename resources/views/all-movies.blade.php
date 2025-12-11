{{-- resources/views/all-movies.blade.php --}}
@extends('layouts.app')

@section('title', 'Tất cả phim')

@section('content')

{{-- ==================== HEADER ==================== --}}
<section class="pt-20 pb-12 bg-gradient-to-b from-gray-900 to-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-gray-900 mb-4">
            Tất cả phim
        </h1>
        <p class="text-gray-600 text-lg">Tìm và đặt vé phim yêu thích của bạn</p>
    </div>
</section>

{{-- ==================== BỘ LỌC ==================== --}}
@php
    $currentTab = request('tab', 'showing');
    $commonFilters = request()->only(['search', 'category']);
@endphp

<section class="bg-gray-50 py-8 sticky top-0 z-20 border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Tabs --}}
        <div class="flex gap-8 mb-6 border-b border-gray-300">
            <a href="{{ route('movies.all', array_merge($commonFilters, ['tab' => 'showing'])) }}"
               class="pb-4 px-1 font-bold text-lg transition-all relative
                      {{ $currentTab === 'showing' ? 'text-purple-600 after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-gradient-to-r after:from-purple-600 after:to-pink-600 after:rounded-t-lg' : 'text-gray-600 hover:text-gray-900' }}">
                Phim Đang Chiếu
            </a>

            <a href="{{ route('movies.all', array_merge($commonFilters, ['tab' => 'upcoming'])) }}"
               class="pb-4 px-1 font-bold text-lg transition-all relative
                      {{ $currentTab === 'upcoming' ? 'text-purple-600 after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-gradient-to-r after:from-purple-600 after:to-pink-600 after:rounded-t-lg' : 'text-gray-600 hover:text-gray-900' }}">
                Phim Sắp Chiếu
            </a>

            <a href="{{ route('movies.all', array_merge($commonFilters, ['tab' => 'special'])) }}"
               class="pb-4 px-1 font-bold text-lg transition-all relative flex items-center gap-2
                      {{ $currentTab === 'special' ? 'text-yellow-600 after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-gradient-to-r after:from-yellow-400 after:via-orange-500 after:to-pink-500 after:rounded-t-lg after:shadow-lg' : 'text-gray-600 hover:text-yellow-600' }}">
                Suất Chiếu Sớm
                <span class="text-xs bg-yellow-500 text-black px-2 py-0.5 rounded-full font-bold animate-pulse">HOT</span>
            </a>
        </div>

        {{-- Form lọc --}}
        <form method="GET" action="{{ route('movies.all') }}" class="space-y-6">
            <input type="hidden" name="tab" value="{{ $currentTab }}">

            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-5">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">Search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Tìm tên phim..."
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>

                <div class="md:col-span-4">
                    <select name="category"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Tất cả thể loại</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->cate_id }}" {{ request('category') == $cat->cate_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 flex items-center justify-center gap-2">
                        Lọc
                    </button>
                </div>
            </div>

            <div class="grid md:grid-cols-12 gap-4">
                @if($currentTab === 'showing')
                    <div class="md:col-span-4">
                        <select name="cinema"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">Tất cả rạp</option>
                            @foreach($cinemas as $cinema)
                                <option value="{{ $cinema->cinema_id }}" {{ request('cinema') == $cinema->cinema_id ? 'selected' : '' }}>
                                    {{ $cinema->cinema_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="md:col-span-4"></div>
                @endif

                <div class="md:col-span-4">
                    <input type="date"
                        name="{{ $currentTab === 'showing' ? 'show_date' : ($currentTab === 'special' ? 'early_date' : 'release_date') }}"
                        value="{{ request($currentTab === 'showing' ? 'show_date' : ($currentTab === 'special' ? 'early_date' : 'release_date')) }}"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="md:col-span-4">
                    <a href="{{ route('movies.all', ['tab' => $currentTab]) }}"
                       class="w-full block text-center px-4 py-2.5 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- ==================== NỘI DUNG ==================== --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">

        {{-- TAB: PHIM ĐANG CHIẾU --}}
        @if($currentTab === 'showing')
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-900 mb-8">
                    Phim Đang Chiếu
                    <span class="text-gray-500 text-lg">{{ $showingMovies->total() }} phim</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @forelse($showingMovies as $movie)
                        <div class="group relative transform transition-all duration-300 hover:scale-105">
                            <a href="{{ route('movie.detail', $movie->slug) }}" class="block">
                                <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gray-200 aspect-[2/3]">
                                    @if($movie->age_limit)
                                        <div class="absolute top-3 left-3 z-20">
                                            <span class="px-2.5 py-1 bg-gray-900/80 text-white text-xs font-bold rounded-lg backdrop-blur-sm">
                                                {{ $movie->age_limit }}+
                                            </span>
                                        </div>
                                    @endif

                                    <img src="{{ $movie->poster ? asset('poster/'.basename($movie->poster)) : 'https://via.placeholder.com/400x600' }}"
                                         alt="{{ $movie->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                        <button class="w-full py-3 bg-purple-600 text-white font-bold rounded-xl shadow-lg hover:bg-purple-700">
                                            Đặt vé
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-purple-600">
                                        {{ $movie->title }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $movie->category->name ?? 'Phim' }}</span>
                                        @if($movie->duration)
                                            <span>{{ $movie->duration }}'</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <p class="text-2xl text-gray-500">Không tìm thấy phim nào</p>
                        </div>
                    @endforelse
                </div>

                {{ $showingMovies->appends(request()->query())->links() }}
            </div>

        {{-- TAB: PHIM SẮP CHIẾU --}}
        @elseif($currentTab === 'upcoming')
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-900 mb-8">
                    Phim Sắp Chiếu
                    <span class="text-gray-500 text-lg">{{ $upcomingMovies->total() }} phim</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @forelse($upcomingMovies as $movie)
                        @php
                            $date = $movie->release_date ? \Carbon\Carbon::parse($movie->release_date) : null;
                        @endphp
                        <div class="group relative transform transition-all duration-300 hover:scale-105">
                            <a href="{{ route('movie.detail', $movie->slug) }}" class="block">
                                <div class="relative overflow-hidden rounded-2xl bg-slate-800 aspect-[2/3]">
                                    <img src="{{ $movie->poster ? asset('poster/'.basename($movie->poster)) : 'https://via.placeholder.com/400x600' }}"
                                         alt="{{ $movie->title }}"
                                         class="w-full h-full object-cover brightness-75 group-hover:brightness-100 group-hover:scale-105 transition-all duration-500">

                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black via-black/70 to-transparent">
                                        <span class="text-purple-400 font-bold text-sm">
                                            {{ $date ? $date->format('d/m/Y') : 'Chưa công bố' }}
                                        </span>
                                        <h4 class="text-white font-bold text-base line-clamp-1 group-hover:text-purple-300">
                                            {{ $movie->title }}
                                        </h4>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-purple-600">
                                        {{ $movie->title }}
                                    </h3>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $movie->category->name ?? 'Phim' }}</span>
                                        <span class="ml-2 text-purple-600 font-bold">
                                            {{ $date ? $date->format('d/m/Y') : 'TBA' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <p class="text-2xl text-gray-500">Chưa có phim sắp chiếu</p>
                        </div>
                    @endforelse
                </div>

                {{ $upcomingMovies->appends(request()->query())->links() }}
            </div>

        {{-- TAB: SUẤT CHIẾU SỚM --}}
        @elseif($currentTab === 'special')
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-900 mb-8">
                    Suất Chiếu Sớm Đặc Biệt
                    <span class="text-gray-500 text-lg">{{ $specialMovies->total() }} phim</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @forelse($specialMovies as $movie)
                        @php
                            $earlyDate = \Carbon\Carbon::parse($movie->early_premiere_date);
                            $officialDate = $movie->release_date ? \Carbon\Carbon::parse($movie->release_date) : null;
                        @endphp

                        <div class="group relative transform transition-all duration-300 hover:scale-105">
                            <a href="{{ route('movie.detail', $movie->slug) }}" class="block">
                                <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gray-200 aspect-[2/3]">
                                    {{-- Badge Chiếu Sớm --}}
                                    <div class="absolute top-3 left-3 z-20 flex flex-wrap gap-2">
                                        <span class="px-2.5 py-1 bg-yellow-500 text-black text-xs font-bold rounded-lg shadow-md">
                                            CHIẾU SỚM
                                        </span>
                                        @if($movie->age_limit)
                                            <span class="px-2.5 py-1 bg-gray-900/80 text-white text-xs font-bold rounded-lg backdrop-blur-sm">
                                                {{ $movie->age_limit }}+
                                            </span>
                                        @endif
                                    </div>

                                    <img src="{{ $movie->poster ? asset('poster/'.basename($movie->poster)) : 'https://via.placeholder.com/400x600/1a1a2e/ffd700?text=' . urlencode($movie->title) }}"
                                         alt="{{ $movie->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                        <button class="w-full py-3 bg-yellow-500 text-black font-bold rounded-xl shadow-lg hover:bg-yellow-600 transition">
                                            Đặt vé sớm
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-yellow-600 transition">
                                        {{ $movie->title }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $movie->category->name ?? 'Phim' }}</span>
                                        <span class="font-bold text-yellow-600">
                                            {{ $earlyDate->translatedFormat('d/m/Y') }}
                                        </span>
                                    </div>
                                    @if($officialDate)
                                        <p class="text-xs text-gray-400 mt-1">
                                            Chính thức: {{ $officialDate->translatedFormat('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <p class="text-2xl text-gray-500">Hiện chưa có suất chiếu sớm nào</p>
                            <p class="text-gray-400 mt-2">Hãy theo dõi thường xuyên để không bỏ lỡ!</p>
                        </div>
                    @endforelse
                </div>

                {{ $specialMovies->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</section>

@endsection