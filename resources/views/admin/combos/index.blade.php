@extends('admin.layouts.app')

@section('title', 'Quản lý Combo')

@section('content')
{{-- Thay đổi nền chính --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-1">
                    QUẢN LÝ COMBO
                </h1>
                {{-- Giả định biến $combos là đối tượng paginate --}}
                <p class="text-purple-300 text-sm sm:text-base">Tổng cộng: <span class="font-black text-pink-400">{{ $combos->total() }}</span> combo</p>
            </div>
            <a href="{{ route('admin.combos.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-2.5 px-6 rounded-xl shadow-xl text-sm transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i> THÊM COMBO MỚI
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border-2 border-green-500 text-green-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
            </div>
        @endif

{{-- ==================== BỘ LỌC COMBO ==================== --}}
        <form method="GET" action="{{ route('admin.combos.index') }}" class="mb-10">
            <div class="bg-gradient-to-r from-purple-900/40 via-pink-900/40 to-purple-900/40 backdrop-blur-2xl rounded-3xl p-8 shadow-2xl border border-white/10 ring-1 ring-white/5">

                {{-- TIÊU ĐỀ --}}
                <div class="text-center mb-7">
                    <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-pink-300">
                        <i class="fas fa-filter mr-3"></i>BỘ LỌC TÌM KIẾM COMBO
                    </h3>
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- 1. Tên Combo --}}
                    <div class="filter-box group lg:col-span-2">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Tên Combo
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Nhập tên combo..."
                            class="modern-input">
                    </div>

                    {{-- 2. Trạng thái --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-toggle-on"></i> Trạng thái
                        </label>
                        <select name="status" class="modern-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="0" {{ request('status') == '0' && request('status') != null ? 'selected' : '' }}>Đã ẩn</option>
                        </select>
                    </div>

                    {{-- 3. Sắp xếp --}}
                    <div class="filter-box group">
                        <label class="filter-label">
                            <i class="fas fa-sort"></i> Sắp xếp
                        </label>
                        <select name="sort" class="modern-select">
                            <option value="">Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-black rounded-2xl shadow-2xl transition transform hover:scale-105 flex items-center gap-3 text-lg">
                        <i class="fas fa-sparkles"></i> LỌC NGAY
                    </button>

                    @if(request()->hasAny(['search','status','sort']))
                        <a href="{{ route('admin.combos.index') }}"
                            class="px-8 py-4 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-bold rounded-2xl shadow-xl transition transform hover:scale-105 flex items-center gap-3 text-lg">
                            <i class="fas fa-undo"></i> XÓA LỌC
                        </a>
                    @endif
                </div>

            </div>
        </form>


        @if($combos->count() > 0)
            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl overflow-hidden border border-white/20">
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-sm">
                        <thead class="bg-gradient-to-r from-purple-600/40 to-pink-600/40 border-b border-white/20">
                            <tr>
                                <th class="py-4 px-4 text-left font-black w-20">Ảnh</th>
                                <th class="py-4 px-4 text-left font-black">Tên Combo</th>
                                <th class="py-4 px-4 text-left font-black max-w-xs">Mô tả</th>
                                <th class="py-4 px-4 text-right font-black w-40">Giá</th>
                                <th class="py-4 px-4 text-center font-black w-36">Trạng thái</th>
                                <th class="py-4 px-4 text-center font-black w-56">Hành động</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($combos as $combo)
                            <tr class="border-b border-white/10 hover:bg-white/5 transition duration-300">
                                {{-- Poster (ĐÃ KHẮC PHỤC ĐƯỜNG DẪN ẢNH) --}}
                                <td class="py-3 px-4">
                                    <img src="{{ $combo->image ? asset('images/combos/' . $combo->image) : asset('images/combos/no-image.jpg') }}" 
                                        alt="{{ $combo->combo_name }}"
                                        class="w-16 h-12 object-cover rounded-md shadow-md hover:scale-105 transition">
                                </td>
                                {{-- Tên Combo --}}
                                <td class="py-3 px-4">
                                    <p class="font-bold text-white">{{ $combo->combo_name }}</p>
                                </td>
                                {{-- Mô tả --}}
                                <td class="py-3 px-4 text-xs text-purple-300 truncate max-w-xs">
                                    {{ $combo->description ?? 'Không có mô tả.' }}
                                </td>
                                {{-- Giá --}}
                                <td class="py-3 px-4 text-right font-black text-green-400">
                                    {{ number_format($combo->price) }}₫
                                </td>
                                {{-- Trạng thái --}}
                                <td class="py-3 px-4 text-center">
                                    @if($combo->status == 1)
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-green-500/30 text-green-200 border border-green-500"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-red-500/30 text-red-200 border border-red-500"><i class="fas fa-eye-slash mr-1"></i> Đã ẩn</span>
                                    @endif
                                </td>
                                {{-- Hành động --}}
                                <td class="py-3 px-4">
                                    <div class="flex gap-2 justify-center flex-wrap">
                                        {{-- Sửa --}}
                                        <a href="{{ route('admin.combos.edit', $combo) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1"><i class="fas fa-edit"></i> Sửa</a>
                                        
                                        {{-- Ẩn/Hiện --}}
                                        @if($combo->status == 1)
                                            <form action="{{ route('admin.combos.deactivate', $combo) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Bạn chắc chắn muốn ẩn combo này?')" 
                                                    class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                    <i class="fas fa-eye-slash"></i> Ẩn
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.combos.activate', $combo) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Bạn chắc chắn muốn hiện lại combo này?')" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                    <i class="fas fa-eye"></i> Hiện
                                                </button>
                                            </form>
                                        @endif

                                        {{-- XÓA VĨNH VIỄN (ĐÃ BỔ SUNG) --}}
                                        <form action="{{ route('admin.combos.destroy', $combo) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('CẢNH BÁO: Bạn chắc chắn muốn XÓA VĨNH VIỄN combo này? Hành động này không thể hoàn tác!')" 
                                                class="bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="lg:hidden space-y-4">
                @foreach($combos as $combo)
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg border border-white/20 p-4 hover:border-purple-500/50 transition">
                    <div class="flex gap-3 mb-3">
                        {{-- Ảnh Mobile (ĐÃ KHẮC PHỤC ĐƯỜNG DẪN ẢNH) --}}
                        <img src="{{ $combo->image ? asset($combo->image) : asset('images/combos/no-image.jpg') }}" 
                            alt="{{ $combo->combo_name }}"
                            class="w-20 h-16 object-cover rounded-lg shadow-md">
                        <div class="flex-1">
                            <h3 class="text-base font-black text-white mb-1">{{ $combo->combo_name }}</h3>
                            <p class="text-xs text-purple-300 mb-2">Giá: <span class="font-bold text-green-400">{{ number_format($combo->price) }}₫</span></p>
                            @if($combo->status == 1)
                                <span class="inline-block px-3 py-1 rounded-full font-bold text-xs bg-green-500/30 text-green-200 border border-green-500"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full font-bold text-xs bg-red-500/30 text-red-200 border border-red-500"><i class="fas fa-eye-slash mr-1"></i> Đã ẩn</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex gap-2 text-xs pt-3 border-t border-white/10"> 
                        {{-- Sửa --}}
                        <a href="{{ route('admin.combos.edit', $combo) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-bold text-center transition"><i class="fas fa-edit mr-1"></i> Sửa</a>
                        
                        {{-- Ẩn/Hiện --}}
                        @if($combo->status == 1)
                            <form action="{{ route('admin.combos.deactivate', $combo) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Ẩn combo này?')" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg font-bold transition">
                                    <i class="fas fa-eye-slash mr-1"></i> Ẩn
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.combos.activate', $combo) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Hiện lại combo này?')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-bold transition">
                                    <i class="fas fa-eye mr-1"></i> Hiện
                                </button>
                            </form>
                        @endif

                        {{-- XÓA VĨNH VIỄN (ĐÃ BỔ SUNG) --}}
                        <form action="{{ route('admin.combos.destroy', $combo) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('CẢNH BÁO: Bạn chắc chắn muốn XÓA VĨNH VIỄN combo này? Hành động này không thể hoàn tác!')" 
                                class="w-full bg-red-700 hover:bg-red-800 text-white px-3 py-2 rounded-lg font-bold transition">
                                <i class="fas fa-trash mr-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 py-20 text-center">
                <i class="fas fa-box-open text-6xl text-purple-400 opacity-40 mb-4"></i>
                <p class="text-xl font-black text-purple-300">Không tìm thấy Combo nào</p>
                <a href="{{ route('admin.combos.create') }}" class="inline-block mt-6 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-3 px-6 rounded-xl shadow-xl text-base transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Thêm Combo đầu tiên
                </a>
            </div>
        @endif

        {{-- PAGINATION --}}
        @if($combos->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 p-4">
                    {{ $combos->links('pagination::tailwind') }}
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
        color: #d8b4fe; /* purple-300 */
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
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ec4899' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); /* pink-500 */
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
    }
    .modern-select option {
        background: #1e1b4b !important; /* purple-900 */
        color: white !important;
    }

    /* Input placeholder */
    .modern-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
</style>
@endsection