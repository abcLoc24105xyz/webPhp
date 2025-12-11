{{-- resources/views/movie/detail.blade.php --}}
@extends('layouts.app')
@section('title', $movie->title)
@section('content')

@php
    $earlyDate = $earlyPremiereDate ?? null;
    $releaseDate = $movie->release_date ? \Carbon\Carbon::parse($movie->release_date) : null;
    $isReleased = $releaseDate && $releaseDate->lte(now());

    // Kiểm tra có suất chiếu nào trong tương lai không
    $hasUpcomingShows = $availableDates->contains(function ($date) {
        return \Carbon\Carbon::parse($date)->gte(now()->startOfDay());
    });
@endphp

<style>
    .step-circle { transition: all 0.3s ease; }
    .step-circle-base { width: 3rem; height: 3rem; font-size: 1rem; }
    .step-circle-active { width: 3.5rem; height: 3.5rem; font-size: 1.5rem; animation: pulse 2s infinite; }
    .step-text { transition: all 0.3s ease; }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 20px rgba(250, 204, 21, 0.6); }
        50% { box-shadow: 0 0 40px rgba(250, 204, 21, 1); }
    }

    .progress-bar {
        background: linear-gradient(90deg, #9333ea, #ec4899);
        transition: width 0.8s ease;
        box-shadow: 0 0 15px rgba(147, 51, 234, 0.6);
    }

    .glass-effect {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(148, 163, 184, 0.3);
    }

    .filter-inactive {
        background: rgba(45, 53, 72, 0.6);
        backdrop-filter: blur(10px) saturate(150%);
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .progress-scrolled .step-text { opacity: 0; height: 0; margin: 0 !important; overflow: hidden; }
    .progress-scrolled .step-circle-base { width: 2.5rem !important; height: 2.5rem !important; }
    .progress-scrolled .step-circle-active { width: 3rem !important; height: 3rem !important; }

    #content-start { padding-top: calc(7rem + 1px); }
</style>

{{-- Thanh tiến trình (Sticky) - chỉ hiện khi đang đặt vé --}}
@auth
<div id="progress-header" class="fixed top-20 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-md border-b border-white/10 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 py-3 md:py-4">
        <div class="grid grid-cols-5 gap-2 text-center mb-2">
            @for($i = 1; $i <= 5; $i++)
                @php
                    $opacityClass = $i === 1 ? '' : 'opacity-' . (100 - $i * 15);
                    $circleClass = $i === 1 ? 'step-circle-active' : 'step-circle-base';
                    $bgClass = $i === 1 ? 'bg-gradient-to-r from-purple-500 to-pink-500 ring-2 ring-purple-400/50' : 'bg-white/10';
                    $textClass = $i === 1 ? 'text-white' : 'text-gray-' . (400 + $i * 50);
                    $steps = ['Chọn rạp & suất chiếu', 'Chọn ghế', 'Combo', 'Xác nhận', 'Thanh toán'];
                @endphp
                <div class="flex flex-col items-center {{ $opacityClass }}">
                    <div class="step-circle {{ $circleClass }} rounded-full flex items-center justify-center font-black text-white {{ $bgClass }} shadow-xl">{{ $i }}</div>
                    <p class="mt-1 text-xs font-bold {{ $textClass }} step-text">{{ $steps[$i-1] }}</p>
                </div>
            @endfor
        </div>
        <div class="relative h-1 bg-white/10 rounded-full overflow-hidden">
            <div class="progress-bar h-full rounded-full" style="width: 20%"></div>
        </div>
    </div>
</div>
@endauth

<div id="content-start">
    {{-- Banner suất chiếu sớm --}}
    @if($hasEarlyPremiere && $earlyDate)
        <div class="relative overflow-hidden bg-gradient-to-r from-yellow-400 via-orange-500 to-pink-500 py-10 md:py-12">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="absolute top-0 left-0 w-64 h-64 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="relative max-w-6xl mx-auto px-4 text-center">
                <div class="inline-flex flex-col md:flex-row items-center justify-center gap-4 bg-black/60 backdrop-blur-xl px-8 py-6 rounded-3xl shadow-2xl border-4 border-yellow-300">
                    <svg class="w-16 h-16 text-yellow-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.953a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.374 2.456a1 1 0 00-.364 1.118l1.287 3.953c.3.921-.755 1.688-1.54 1.118l-3.374-2.456a1 1 0 00-1.175 0L6.337 18.69c-.784.57-1.838-.197-1.54-1.118l1.287-3.953a1 1 0 00-.364-1.118L2.346 9.38c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.953z"/>
                    </svg>
                    <div class="text-white">
                        <h2 class="text-3xl md:text-5xl font-heading font-black drop-shadow-2xl">SUẤT CHIẾU SỚM ĐẶC BIỆT</h2>
                        <p class="text-2xl md:text-4xl font-bold mt-2 text-yellow-100">{{ $earlyDate->translatedFormat('D, d/m/y') }}</p>
                        <p class="text-lg mt-3 opacity-90">Đặt vé ngay để là người đầu tiên thưởng thức!</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($movie->early_premiere_date && \Carbon\Carbon::parse($movie->early_premiere_date)->isPast())
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 py-4 text-center">
            <p class="text-lg font-bold text-gray-300">Suất chiếu sớm đã diễn ra vào {{ \Carbon\Carbon::parse($movie->early_premiere_date)->translatedFormat('d/m/Y') }}</p>
        </div>
    @endif
</div>

{{-- Thông tin phim --}}
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-black text-white py-16 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl md:text-6xl font-heading font-black text-center text-yellow-400 mb-12 drop-shadow-2xl">{{ $movie->title }}</h1>
        <div class="grid lg:grid-cols-3 gap-10 items-start">
            <div class="lg:col-span-1">
                <div class="rounded-3xl shadow-2xl overflow-hidden border-8 border-purple-500/60">
                    <img src="{{ $movie->poster ? asset('poster/' . basename($movie->poster)) : 'https://via.placeholder.com/600x900' }}"
                         alt="{{ $movie->title }}" class="w-full h-auto object-cover">
                </div>
            </div>
            <div class="lg:col-span-2 space-y-8">
                <div class="glass-effect rounded-3xl p-8 border border-white/20">
                    <h3 class="text-2xl font-black text-white mb-6 border-b border-white/10 pb-3">Thông tin phim</h3>
                    <div class="grid md:grid-cols-2 gap-6 text-lg">
                        <div>
                            <p><span class="font-bold text-gray-300">Đạo diễn:</span> <span class="text-yellow-300 font-semibold ml-3">{{ $movie->director }}</span></p>
                            <p><span class="font-bold text-gray-300">Thể loại:</span> <span class="text-white font-medium ml-3">{{ $movie->category->name ?? 'Phim lẻ' }}</span></p>
                            <p><span class="font-bold text-gray-300">Thời lượng:</span> <span class="text-white font-medium ml-3">{{ $movie->duration }} phút</span></p>
                        </div>
                        <div>
                            <p><span class="font-bold text-gray-300">Giới hạn tuổi:</span> <span class="bg-red-600 px-6 py-3 ml-4 rounded-full font-black text-2xl shadow-lg inline-block">{{ $movie->age_limit ?? 'T' }}+</span></p>
                            <p><span class="font-bold text-gray-300">Khởi chiếu:</span> <span class="text-yellow-400 font-black text-2xl ml-4">{{ $releaseDate ? $releaseDate->translatedFormat('d/m/Y') : 'Chưa công bố' }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="glass-effect rounded-3xl p-8 border border-white/20">
                    <h3 class="text-2xl font-black text-white mb-6 border-b border-white/10 pb-3">Nội dung phim</h3>
                    <p class="text-lg leading-relaxed text-gray-100">{{ $movie->description }}</p>
                </div>
                @if($movie->trailer)
                    <div>
                        <h3 class="text-3xl font-black mb-6">Trailer chính thức</h3>
                        <div class="relative w-full rounded-3xl overflow-hidden shadow-2xl border-4 border-purple-500/50" style="padding-bottom: 56.25%;">
                            <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($movie->trailer, 'v=') }}" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- CHỈ HIỆN PHẦN NÀY KHI CÓ SUẤT CHIẾU TRONG TƯƠNG LAI --}}
@if($hasUpcomingShows)

    {{-- Bộ lọc --}}
    <div class="py-12 px-4 bg-slate-900/50 backdrop-blur-sm border-t border-b border-white/10">
        <div class="max-w-6xl mx-auto">
            {{-- Lọc theo rạp --}}
            <div class="mb-10">
                <h3 class="text-white font-bold text-3xl mb-6 text-center md:text-left border-b-2 border-purple-500/50 pb-2 inline-block">Lọc theo rạp</h3>
                <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                    <button type="button" onclick="filterByCinema(null, '{{ $selectedDate->format('Y-m-d') }}')"
                            class="cinema-filter-btn group relative px-8 py-4 rounded-full font-extrabold text-lg transition-all duration-300 transform hover:scale-[1.03] {{ is_null($selectedCinemaId) ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-2xl shadow-purple-500/50 ring-4 ring-purple-400/70' : 'filter-inactive text-gray-300 hover:bg-white/10 hover:text-white' }}"
                            data-cinema-id="">
                        <span class="relative z-10 flex items-center gap-2">
                            Tất cả rạp
                        </span>
                    </button>

                    @forelse($availableCinemas as $cinema)
                        <button type="button" onclick="filterByCinema({{ $cinema->cinema_id }}, '{{ $selectedDate->format('Y-m-d') }}')"
                                class="cinema-filter-btn group relative px-8 py-4 rounded-full font-extrabold text-lg transition-all duration-300 transform hover:scale-[1.03] {{ $selectedCinemaId == $cinema->cinema_id ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-black shadow-2xl shadow-yellow-500/50 ring-4 ring-yellow-300/80' : 'filter-inactive text-gray-300 hover:bg-white/10 hover:text-white' }}"
                                data-cinema-id="{{ $cinema->cinema_id }}">
                            <span class="relative z-10">{{ $cinema->cinema_name }}</span>
                        </button>
                    @empty
                        <div class="text-gray-400 text-center w-full py-8 font-medium">Không có rạp nào đang chiếu phim này</div>
                    @endforelse
                </div>
            </div>

            <div class="my-12 border-t border-white/10"></div>

            {{-- Chọn ngày --}}
            <div>
                <h3 class="text-white font-bold text-3xl mb-6 text-center md:text-left border-b-2 border-yellow-500/50 pb-2 inline-block">Chọn ngày chiếu</h3>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    @php
                        $uniqueDates = $availableDates->filter(function ($date) {
                            return \Carbon\Carbon::parse($date)->gte(now()->startOfDay());
                        })->unique()->values();

                        if ($earlyDate && $earlyDate->gte(now()->startOfDay())) {
                            $uniqueDates = $uniqueDates->sortBy(function ($date) use ($earlyDate) {
                                return \Carbon\Carbon::parse($date)->toDateString() === $earlyDate->toDateString() ? -1 : 1;
                            })->values();
                        }
                    @endphp

                    @foreach($uniqueDates as $dateStr)
                        @php
                            $d = \Carbon\Carbon::parse($dateStr);
                            $isActive = $d->toDateString() === $selectedDate->toDateString();
                            $isEarly = $earlyDate && $d->toDateString() === $earlyDate->toDateString();
                        @endphp
                        <button type="button" data-date="{{ $d->format('Y-m-d') }}"
                                class="date-tab relative w-[100px] h-[110px] flex flex-col justify-center items-center rounded-2xl font-bold text-center transition-all duration-300 transform {{ $isActive ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-black scale-105 shadow-xl shadow-yellow-500/50 ring-4 ring-yellow-300' : 'filter-inactive text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <div class="text-4xl font-black">{{ $d->format('d') }}</div>
                            <div class="text-sm uppercase font-extrabold mt-1 {{ $isActive ? 'text-gray-900' : 'text-gray-200' }}">{{ $d->translatedFormat('D') }}</div>
                            <div class="text-xs mt-0.5 {{ $isActive ? 'text-gray-700' : 'text-gray-400' }}">{{ $d->translatedFormat('M, Y') }}</div>
                            @if($isEarly)
                                <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-black animate-pulse shadow-lg z-10">SỚM</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách suất chiếu --}}
    <div class="py-16 bg-gradient-to-br from-slate-800 to-black px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl font-heading font-black text-center text-white mb-10">
                {{ $selectedCinemaId ? 'Suất chiếu - ' . ($availableCinemas->firstWhere('cinema_id', $selectedCinemaId)->cinema_name ?? '') : 'Suất chiếu tất cả rạp' }}
            </h2>

            <div id="showtimes-container" class="space-y-8">
                @forelse($shows as $cinemaId => $showGroup)
                    @php $cinema = $showGroup->first()->cinema @endphp
                    <div class="group bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-500">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-5 md:p-6">
                            <h3 class="text-2xl font-heading font-extrabold text-white">{{ $cinema->cinema_name }}</h3>
                            <p class="mt-1 text-purple-100 text-sm opacity-90">{{ $cinema->address }}</p>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                                @foreach($showGroup as $show)
                                    @php $startTime = \Carbon\Carbon::parse($show->start_time); @endphp

                                    @auth
                                        <a href="{{ route('seat.selection', $show->show_id) }}"
                                           class="group/show relative overflow-hidden rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 p-4 text-white text-center shadow-lg w-[140px] md:w-[150px] transform transition-all duration-300 hover:scale-[1.05] hover:shadow-purple-500/40">
                                            <div class="text-3xl font-black tracking-tight">{{ $startTime->format('H:i') }}</div>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm font-semibold opacity-90 truncate">{{ $show->room->room_name ?? 'Phòng thường' }}</p>
                                                <p class="text-xs bg-white/20 px-3 py-1 rounded-full inline-block font-medium">Còn <strong>{{ $show->remaining_seats }}</strong> ghế</p>
                                            </div>
                                        </a>
                                    @else
                                        <button type="button"
                                                onclick="window.location.href = '{{ route('seat.selection', $show->show_id) }}'"
                                                class="group/show relative overflow-hidden rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 p-4 text-center text-gray-500 font-bold text-lg w-[140px] md:w-[150px] h-[148px] hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 flex flex-col justify-center items-center cursor-pointer">
                                            <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="text-sm">Đăng nhập để đặt vé</span>
                                        </button>
                                    @endauth
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="inline-block bg-white/10 backdrop-blur-xl rounded-2xl px-12 py-10 border border-white/20">
                            <p class="text-4xl font-black text-gray-400">Không có suất chiếu nào</p>
                            <p class="text-xl text-gray-500 mt-4">Hãy chọn ngày hoặc rạp khác!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@else
    {{-- KHÔNG CÓ SUẤT CHIẾU TRONG TƯƠNG LAI → HIỆN THÔNG BÁO ĐẸP --}}
    <div class="py-32 bg-gradient-to-b from-slate-900 to-black px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-block bg-white/10 backdrop-blur-xl rounded-3xl px-16 py-20 border border-white/20 shadow-2xl">
                @if(!$isReleased)
                    <h2 class="text-5xl md:text-4xl font-black text-purple-400 mb-6">SẮP CHIẾU</h2>
                    <p class="text-2xl text-gray-300 mb-8">Phim sẽ chính thức khởi chiếu vào</p>
                    <p class="text-6xl font-black text-yellow-400">{{ $releaseDate?->translatedFormat('d/m/Y') ?? 'Chưa xác định' }}</p>
                    <p class="text-xl text-gray-400 mt-8">Hãy theo dõi để đặt vé sớm nhé!</p>
                @else
                    <h2 class="text-5xl md:text-4xl font-black text-red-500 mb-6">HIỆN TẠI CHƯA CÓ SUẤT CHIẾU</h2>
                    <p class="text-2xl text-gray-300">Cảm ơn bạn đã quan tâm đến bộ phim này! Bạn có thể xem danh sách phim có suất chiếu bằng liên kết dưới đây</p>
                    <a href="{{ route('movies.all', ['tab' => 'showing']) }}"
                    class="inline-block mt-10 px-12 py-5 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-xl rounded-full hover:scale-105 transition-transform shadow-2xl">
                        Xem phim đang chiếu
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- JavaScript - chỉ chạy khi có suất chiếu --}}
@if($hasUpcomingShows)
<script>
    const urlParams = new URLSearchParams(window.location.search);
    let currentDate = urlParams.get('date') || '{{ $selectedDate->format('Y-m-d') }}';
    let currentCinemaId = urlParams.get('cinema') ? parseInt(urlParams.get('cinema')) : null;

    function filterByCinema(cinemaId, date) {
        currentCinemaId = cinemaId;
        document.querySelectorAll('.cinema-filter-btn').forEach(btn => {
            btn.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-2xl', 'shadow-purple-500/50', 'ring-4', 'ring-purple-400/70',
                                  'from-yellow-400', 'to-orange-500', 'text-black', 'shadow-yellow-500/50', 'ring-yellow-300/80');
            btn.classList.add('filter-inactive', 'text-gray-300');
        });

        const selector = cinemaId === null ? '[data-cinema-id=""]' : `[data-cinema-id="${cinemaId}"]`;
        const btn = document.querySelector('.cinema-filter-btn' + selector);
        if (btn) {
            btn.classList.remove('filter-inactive', 'text-gray-300');
            if (cinemaId === null) {
                btn.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-2xl', 'shadow-purple-500/50', 'ring-4', 'ring-purple-400/70');
            } else {
                btn.classList.add('bg-gradient-to-r', 'from-yellow-400', 'to-orange-500', 'text-black', 'shadow-2xl', 'shadow-yellow-500/50', 'ring-4', 'ring-yellow-300/80');
            }
        }
        loadShowtimes(date, cinemaId);
    }

    function loadShowtimes(date, cinemaId) {
        currentDate = date;
        const container = document.getElementById('showtimes-container');
        container.innerHTML = '<div class="text-center py-20"><svg class="animate-spin h-12 w-12 text-yellow-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-xl text-gray-400 mt-4">Đang tải suất chiếu...</p></div>';

        let url = `{{ route('movie.showtimes', $movie->slug) }}`;
        const params = new URLSearchParams();
        params.append('date', date);
        if (cinemaId) params.append('cinema', cinemaId);

        const cleanUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.replaceState(null, '', cleanUrl);

        fetch(url + '?' + params.toString())
            .then(r => r.text())
            .then(html => container.innerHTML = html);
    }

    document.querySelectorAll('.date-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.date-tab').forEach(t => {
                t.classList.remove('bg-gradient-to-r', 'from-yellow-400', 'to-orange-500', 'text-black', 'scale-105', 'ring-4', 'ring-yellow-300', 'shadow-xl', 'shadow-yellow-500/50');
                t.classList.add('filter-inactive', 'text-gray-300');
            });
            this.classList.remove('filter-inactive', 'text-gray-300');
            this.classList.add('bg-gradient-to-r', 'from-yellow-400', 'to-orange-500', 'text-black', 'scale-105', 'ring-4', 'ring-yellow-300', 'shadow-xl', 'shadow-yellow-500/50');
            loadShowtimes(this.dataset.date, currentCinemaId);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const initDate = urlParams.get('date') || '{{ $selectedDate->format('Y-m-d') }}';
        const initCinema = urlParams.get('cinema') ? parseInt(urlParams.get('cinema')) : null;
        filterByCinema(initCinema, initDate);

        document.querySelectorAll('.date-tab').forEach(t => {
            if (t.dataset.date === initDate) {
                t.classList.remove('filter-inactive', 'text-gray-300');
                t.classList.add('bg-gradient-to-r', 'from-yellow-400', 'to-orange-500', 'text-black', 'scale-105', 'ring-4', 'ring-yellow-300', 'shadow-xl', 'shadow-yellow-500/50');
            }
        });
    });

    const progressHeader = document.getElementById('progress-header');
    if (progressHeader) {
        window.addEventListener('scroll', () => {
            progressHeader.classList.toggle('progress-scrolled', window.scrollY > 100);
        });
    }
</script>
@endif
@endsection