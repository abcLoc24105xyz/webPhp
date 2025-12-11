@extends('admin.layouts.app')

@section('title', 'Quản lý suất chiếu')

@section('content')
<style>
    /* CSS cho bộ lọc giữ nguyên */
    .filter-box {
        position: relative;
        background: rgba(0,0,0,0.6);
        border-radius: 18px;
        padding: 14px 18px;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(12px);
    }

    .filter-label {
        font-size: 12px;
        color: #e9d5ff;
        font-weight: 700;
        margin-bottom: 6px;
        display: block;
        text-transform: uppercase;
    }

    .modern-input,
    .modern-select {
        width: 100%;
        background: transparent;
        color: white;
        padding: 6px 0; 
        font-size: 15px;
        outline: none;
    }

    .modern-select {
        appearance: none;
        padding-right: 24px;
        background-image: url("data:image/svg+xml,%3Csvg fill='%23d946ef' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'%3E</path></svg>");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
    }

    .modern-select option {
        background: #1e1b4b;
        color: white;
    }
    
    /* TÙY CHỈNH CHO INPUT DATE TRÊN NỀN ĐEN (Giữ nguyên) */
    .modern-input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) sepia(1) saturate(5) hue-rotate(250deg) brightness(1.5);
        opacity: 1; 
        cursor: pointer;
        margin-right: -0.5rem; 
    }
    .modern-input[type="date"]::-webkit-datetime-edit-month-field, 
    .modern-input[type="date"]::-webkit-datetime-edit-day-field, 
    .modern-input[type="date"]::-webkit-datetime-edit-year-field {
        color: white; 
    }
</style>

{{-- Container chính (Giữ nguyên) --}}
<div class="max-w-7xl mx-auto py-6 sm:py-10">

    {{-- HEADER (Giữ nguyên) --}}
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">
            🎬 Danh sách suất chiếu
        </h1>
        <a href="{{ route('admin.shows.create') }}" 
           class="px-6 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-xl hover:shadow-purple-500/30 transition transform hover:scale-105 text-sm"> {{-- Thêm text-sm cho nút --}}
             <i class="fas fa-plus mr-1"></i> Thêm suất chiếu
        </a>
    </div>

    {{-- SUCCESS MESSAGE (Giữ nguyên) --}}
    @if(session('success'))
        <div class="bg-green-600/20 border border-green-600 text-green-200 px-6 py-4 rounded-2xl mb-6 shadow-md font-bold text-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif


    {{-- ==================== BỘ LỌC (Giữ nguyên cấu trúc) ==================== --}}
    <form method="GET" class="mb-10">
        <div class="bg-gradient-to-r from-purple-900/40 via-pink-900/40 to-purple-900/40 backdrop-blur-xl p-6 rounded-3xl shadow-xl border border-white/10"> {{-- Giảm padding p-8 -> p-6 --}}

            <div class="text-center mb-6"> {{-- Giảm mb-7 -> mb-6 --}}
                <h3 class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-pink-300"> {{-- Giảm text-xl -> text-lg --}}
                    <i class="fas fa-filter mr-2"></i>Bộ lọc suất chiếu
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4"> {{-- Giảm gap-6 -> gap-4 --}}

                {{-- SEARCH --}}
                <div class="filter-box">
                    <label class="filter-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                    <input type="text" name="search" placeholder="Mã suất / Tên phim..."
                            value="{{ request('search') }}" class="modern-input">
                </div>

                {{-- CHỌN RẠP --}}
                <div class="filter-box">
                    <label class="filter-label"><i class="fas fa-building"></i> Rạp</label>
                    <select name="cinema" class="modern-select">
                        <option value="">Tất cả rạp</option>
                        @foreach($cinemas as $c)
                            <option value="{{ $c->cinema_id }}" {{ request('cinema') == $c->cinema_id ? 'selected' : '' }}>
                                {{ $c->cinema_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NGÀY --}}
                <div class="filter-box">
                    <label class="filter-label"><i class="fas fa-calendar"></i> Ngày chiếu</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="modern-input">
                </div>

                {{-- BUTTON --}}
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full py-2.5 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-lg hover:shadow-purple-500/40 transition hover:scale-105 text-sm"> {{-- Giảm py-3 -> py-2.5 --}}
                        <i class="fas fa-check mr-2"></i> Lọc
                    </button>
                </div>

            </div>
        </div>
    </form>


    {{-- ==================== BẢNG (ĐÃ THU GỌN TỐI ĐA) ==================== --}}
    <div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-xl overflow-hidden border border-white/20">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-white text-xs"> {{-- Đặt text-xs ở đây --}}
                
                {{-- HEADER TABLE --}}
                <thead class="bg-gradient-to-r from-purple-600/60 to-pink-600/60 border-b border-white/20">
                    <tr>
                        <th class="px-3 py-2 font-bold whitespace-nowrap">Mã suất</th> {{-- Giảm padding py-3, px-5 -> py-2, px-3 --}}
                        <th class="px-3 py-2 font-bold min-w-[180px]">Phim</th> {{-- Giảm min-w --}}
                        <th class="px-3 py-2 font-bold">Rạp - Phòng</th>
                        <th class="px-3 py-2 font-bold whitespace-nowrap">Ngày chiếu</th>
                        <th class="px-3 py-2 font-bold min-w-[120px] whitespace-nowrap">Giờ chiếu</th> {{-- Giảm min-w --}}
                        <th class="px-3 py-2 font-bold text-center">Ghế còn</th>
                        <th class="px-3 py-2 font-bold text-center min-w-[120px]">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    @forelse($shows as $show)
                    <tr class="hover:bg-white/10 transition duration-200">

                        <td class="px-3 py-2 font-bold text-purple-400">{{ $show->show_id }}</td> {{-- Giảm padding --}}

                        <td class="px-3 py-2"> {{-- Giảm padding --}}
                            {{-- Giảm cỡ chữ tiêu đề phim --}}
                            <div class="font-bold text-sm text-white truncate max-w-[180px]">{{ $show->movie->title }}</div>
                            <small class="text-purple-300 block mt-0.5">{{ $show->movie->duration }} phút</small>
                        </td>

                        <td class="px-3 py-2"> {{-- Giảm padding --}}
                            <div class="font-bold text-white">{{ $show->cinema->cinema_name }}</div>
                            <small class="text-purple-300 block mt-0.5">{{ $show->room->room_name }}</small>
                        </td>

                        <td class="px-3 py-2"> {{-- Giảm padding --}}
                            {{-- Thu gọn badge --}}
                            <span class="bg-blue-800/50 text-blue-300 px-2.5 py-1 rounded-lg font-bold"> {{-- Giảm px/py/rounded --}}
                                {{ $show->show_date->format('d/m/Y') }}
                            </span>
                        </td>

                        <td class="px-3 py-2"> {{-- Giảm padding --}}
                            {{-- Thu gọn badge --}}
                            <span class="bg-pink-800/50 text-pink-300 px-2.5 py-1 rounded-lg font-bold whitespace-nowrap"> {{-- Giảm px/py/rounded --}}
                                {{ substr($show->start_time, 0, 5) }} - {{ substr($show->end_time, 0, 5) }}
                            </span>
                        </td>

                        <td class="px-3 py-2 text-center"> {{-- Giảm padding --}}
                            {{-- Thu gọn badge --}}
                            <span class="px-2.5 py-1 rounded-lg font-bold text-white 
                                {{ $show->remaining_seats == 0 ? 'bg-red-600/80' : 'bg-green-600/80' }}"> {{-- Giảm px/py/rounded --}}
                                {{ $show->remaining_seats }} / {{ $show->room->total_seats }}
                            </span>
                        </td>

                        <td class="px-3 py-2 text-center space-x-1.5 whitespace-nowrap"> {{-- Giảm padding và space --}}
                            <a href="{{ route('admin.shows.edit', $show->show_id) }}" 
                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-lg font-bold transition shadow-md"> {{-- Giảm px/py/rounded --}}
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.shows.destroy', $show->show_id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Xóa suất chiếu này? Chỉ khi chưa có vé được thanh toán!')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg font-bold transition shadow-md"> {{-- Giảm px/py/rounded --}}
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-purple-400/80 text-base"> {{-- Giảm py và text size --}}
                            <i class="fas fa-times-circle text-2xl mb-2 block"></i>
                            Không có suất chiếu nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (Giữ nguyên) --}}
        <div class="p-4 bg-black/30 border-t border-white/10">
            {{ $shows->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection