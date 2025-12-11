@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER (Giữ nguyên) --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-1">
                    QUẢN LÝ PHIM
                </h1>
                <p class="text-purple-300 text-sm sm:text-base">Tổng cộng: <span class="font-black text-pink-400">{{ $movies->total() }}</span> phim</p>
            </div>
            <a href="{{ route('admin.movies.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-2.5 px-6 rounded-xl shadow-xl text-sm transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i> THÊM PHIM MỚI
            </a>
        </div>

        {{-- SUCCESS MESSAGE (Giữ nguyên) --}}
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border-2 border-green-500 text-green-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
            </div>
        @endif

{{-- ==================== BỘ LỌC PHIM ==================== --}}
        <form method="GET" action="{{ route('admin.movies.index') }}" class="mb-10">
            <div class="bg-gradient-to-r from-purple-900/40 via-pink-900/40 to-purple-900/40 backdrop-blur-2xl rounded-3xl p-8 shadow-2xl border border-white/10 ring-1 ring-white/5">

                {{-- TIÊU ĐỀ --}}
                <div class="text-center mb-7">
                    <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-pink-300">
                        <i class="fas fa-filter mr-3"></i>BỘ LỌC TÌM KIẾM PHIM
                    </h3>
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- 1. Tên phim --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Tên phim
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Nhập tên phim..."
                            class="modern-input">
                    </div>

                    {{-- 2. Thể loại --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-tags"></i> Thể loại
                        </label>
                        <select name="category" class="modern-select">
                            <option value="">Tất cả thể loại</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->cate_id }}" 
                                    {{ request('category') == $cat->cate_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Trạng thái --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-play-circle"></i> Trạng thái
                        </label>
                        <select name="status" class="modern-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Sắp chiếu</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Ngừng chiếu</option>
                        </select>
                    </div>

                    {{-- 4. Ngày công chiếu --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-calendar-alt"></i> Ngày công chiếu
                        </label>
                        <input type="date" name="release_date" value="{{ request('release_date') }}"
                            class="modern-input">
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-black rounded-2xl shadow-2xl transition transform hover:scale-105 flex items-center gap-3 text-lg">
                        <i class="fas fa-sparkles"></i> LỌC NGAY
                    </button>

                    @if(request()->hasAny(['search','category','status','release_date']))
                        <a href="{{ route('admin.movies.index') }}"
                            class="px-8 py-4 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-bold rounded-2xl shadow-xl transition transform hover:scale-105 flex items-center gap-3 text-lg">
                            <i class="fas fa-undo"></i> XÓA LỌC
                        </a>
                    @endif
                </div>

            </div>
        </form>


        @if($movies->count() > 0)
            {{-- 3. Desktop Table (Giữ nguyên) --}}
            <div class="hidden lg:block bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl overflow-hidden border border-white/20">
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-sm">
                        <thead class="bg-gradient-to-r from-purple-600/40 to-pink-600/40 border-b border-white/20">
                            <tr>
                                <th class="py-4 px-4 text-left font-black">Poster</th>
                                <th class="py-4 px-4 text-left font-black">Tên phim</th>
                                <th class="py-4 px-4 text-center font-black">Trạng thái</th>
                                <th class="py-4 px-4 text-center font-black">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movies as $movie)
                            <tr class="border-b border-white/10 hover:bg-white/5 transition duration-300">
                                <td class="py-3 px-4">
                                    <img src="{{ $movie->poster ? asset('poster/' . $movie->poster) : asset('poster/no-image.jpg') }}" 
                                         alt="{{ $movie->title }}"
                                         class="w-16 h-24 object-cover rounded-md shadow-md hover:scale-105 transition">
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-white">{{ $movie->title }}</p>
                                    <p class="text-xs text-purple-300 mt-0.5">Category: {{ $movie->category->name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($movie->status == 1)
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-blue-500/30 text-blue-200 border border-blue-500"><i class="fas fa-clock mr-1"></i> Sắp chiếu</span>
                                    @elseif($movie->status == 2)
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-green-500/30 text-green-200 border border-green-500"><i class="fas fa-play-circle mr-1"></i> Đang chiếu</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-red-500/30 text-red-200 border border-red-500"><i class="fas fa-times-circle mr-1"></i> Hết chiếu</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2 justify-center flex-wrap">
                                        <a href="{{ route('admin.movies.edit', $movie->movie_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1"><i class="fas fa-edit"></i> Sửa</a>
                                        <form action="{{ route('admin.movies.toggleStatus', $movie->movie_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                <i class="fas {{ $movie->status == 2 ? 'fa-pause' : 'fa-play' }}"></i>
                                                {{ $movie->status == 2 ? 'Tắt chiếu' : 'Bật lại' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.movies.destroy', $movie->movie_id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa phim này?')" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1"><i class="fas fa-trash-alt"></i> Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. Mobile Cards (Giữ nguyên) --}}
            <div class="lg:hidden space-y-4">
                @foreach($movies as $movie)
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg border border-white/20 p-4 hover:border-purple-500/50 transition">
                    <div class="flex gap-3 mb-3">
                        <img src="{{ $movie->poster ? asset('poster/' . $movie->poster) : asset('poster/no-image.jpg') }}" 
                             alt="{{ $movie->title }}"
                             class="w-20 h-28 object-cover rounded-lg shadow-md">
                        <div class="flex-1">
                            <h3 class="text-base font-black text-white mb-1">{{ $movie->title }}</h3>
                            <p class="text-xs text-purple-300 mb-2">Category: {{ $movie->category->name ?? 'N/A' }}</p>
                            @if($movie->status == 1)
                                <span class="inline-block px-3 py-1 rounded-full font-bold text-xs bg-blue-500/30 text-blue-200 border border-blue-500"><i class="fas fa-clock mr-1"></i> Sắp chiếu</span>
                            @elseif($movie->status == 2)
                                <span class="inline-block px-3 py-1 rounded-full font-bold text-xs bg-green-500/30 text-green-200 border border-green-500"><i class="fas fa-play-circle mr-1"></i> Đang chiếu</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full font-bold text-xs bg-red-500/30 text-red-200 border border-red-500"><i class="fas fa-times-circle mr-1"></i> Hết chiếu</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 text-xs">
                        <a href="{{ route('admin.movies.edit', $movie->movie_id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-bold text-center transition"><i class="fas fa-edit mr-1"></i> Sửa</a>
                        <form action="{{ route('admin.movies.toggleStatus', $movie->movie_id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg font-bold transition">
                                <i class="fas {{ $movie->status == 2 ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                {{ $movie->status == 2 ? 'Tắt chiếu' : 'Bật lại' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.movies.destroy', $movie->movie_id) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Xóa phim này?')" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-bold transition"><i class="fas fa-trash-alt mr-1"></i> Xóa</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Empty State (Giữ nguyên) --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 py-20 text-center">
                <i class="fas fa-film text-6xl text-purple-400 opacity-40 mb-4"></i>
                <p class="text-xl font-black text-purple-300">Thư viện phim trống</p>
                <a href="{{ route('admin.movies.create') }}" class="inline-block mt-6 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-3 px-6 rounded-xl shadow-xl text-base transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Thêm phim đầu tiên
                </a>
            </div>
        @endif

        {{-- PAGINATION (Giữ nguyên) --}}
        @if($movies->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 p-4">
                    {{ $movies->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- SIÊU ĐẸP CSS – ĐỒNG BỘ HOÀN HẢO --}}
<style>
   /* Khối filter */
    .filter-box {
        position: relative;
        background: rgba(0,0,0,0.7);
        border-radius: 16px;
        padding: 16px;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Label */
    .filter-label {
        display: flex;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #d8b4fe;
        margin-bottom: 6px;
    }

    /* Select & Input */
    .modern-select,
    .modern-input {
        width: 100%;
        background: transparent;
        color: white;
        font-size: 14px;
        outline: none;
    }

    /* Select icon */
    .modern-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ec4899' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
    }
    .modern-select option {
        background: #1e1b4b !important;
        color: white !important;
    }

    /* Date icon */
    .modern-input::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(2);
        cursor: pointer;
        opacity: .8;
    }
</style>
@endsection