@extends('admin.layouts.app')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
{{-- Thay đổi nền chính (Giả định layout gốc có nền tối) --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-1">
                    QUẢN LÝ MÃ GIẢM GIÁ
                </h1>
                <p class="text-purple-300 text-sm sm:text-base">Tổng cộng: <span class="font-black text-pink-400">{{ $promocodes->total() }}</span> mã</p>
            </div>
            <a href="{{ route('admin.promocodes.create') }}" 
               class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-2.5 px-6 rounded-xl shadow-xl text-sm transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i> THÊM MÃ MỚI
            </a>
        </div>

        {{-- SUCCESS/ERROR MESSAGE --}}
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border-2 border-green-500 text-green-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-gradient-to-r from-red-500/20 to-pink-500/20 border-2 border-red-500 text-red-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-xl"></i> {{ session('error') }}
            </div>
        @endif

        @if($promocodes->count() > 0)
            {{-- PROMOCODE TABLE (DESKTOP) --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl overflow-hidden border border-white/20">
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-sm">
                        <thead class="bg-gradient-to-r from-purple-600/40 to-pink-600/40 border-b border-white/20">
                            <tr>
                                <th class="px-4 py-3 text-left font-black w-32">MÃ</th>
                                <th class="px-4 py-3 text-left font-black max-w-xs">Mô tả</th>
                                <th class="px-4 py-3 text-center font-black w-28">GIẢM</th>
                                <th class="px-4 py-3 text-center font-black w-36">HIỆU LỰC</th>
                                <th class="px-4 py-3 text-center font-black w-24">GIỚI HẠN</th>
                                <th class="px-4 py-3 text-center font-black w-24">ĐÃ DÙNG</th>
                                {{-- Đã tăng độ rộng cột Trạng thái từ w-32 lên w-36 --}}
                                <th class="px-4 py-3 text-center font-black w-36">TRẠNG THÁI</th> 
                                {{-- Đã tăng độ rộng cột Hành động từ w-40 lên w-48 để chứa thêm nút Xóa --}}
                                <th class="px-4 py-3 text-center font-black w-48">HÀNH ĐỘNG</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promocodes as $p)
                            <tr class="border-b border-white/10 hover:bg-white/5 transition duration-300">
                                {{-- Mã --}}
                                <td class="px-4 py-3 font-extrabold text-pink-300 text-base whitespace-nowrap">{{ $p->promo_code }}</td>

                                {{-- Mô tả --}}
                                <td class="px-4 py-3 text-purple-300 text-xs truncate max-w-xs">{{ $p->description ?? 'Không có mô tả.' }}</td>

                                {{-- Giảm --}}
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if($p->discount_type == 1)
                                        <span class="bg-pink-500/20 text-pink-300 px-3 py-1 rounded-full font-bold text-xs">-{{ $p->discount_value }}%</span>
                                    @else
                                        <span class="bg-pink-500/20 text-pink-300 px-3 py-1 rounded-full font-bold text-xs">-{{ number_format($p->discount_value) }}₫</span>
                                    @endif
                                </td>

                                {{-- Hiệu lực --}}
                                <td class="px-4 py-3 text-center text-xs text-purple-400">
                                    {{ \Carbon\Carbon::parse($p->start_date)->format('d/m/Y') }} 
                                    <span class="text-white mx-1">→</span> 
                                    {{ \Carbon\Carbon::parse($p->end_date)->format('d/m/Y') }}
                                </td>

                                {{-- Giới hạn --}}
                                <td class="px-4 py-3 text-center font-bold">
                                    <span class="text-green-400">{{ $p->usage_limit ?? '∞' }}</span>
                                </td>

                                {{-- Đã dùng --}}
                                <td class="px-4 py-3 text-center font-bold text-yellow-400">
                                    {{ $p->used_count ?? 0 }}
                                </td>

                                {{-- Trạng thái --}}
                                <td class="px-4 py-3 text-center">
                                    @if($p->status == 1)
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-green-600/30 text-green-300 border border-green-600/50"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full font-bold text-xs bg-gray-700/50 text-gray-400 border border-gray-600/50"><i class="fas fa-eye-slash mr-1"></i> Đã tắt</span>
                                    @endif
                                </td>

                                {{-- Hành động --}}
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-2 justify-center flex-wrap">
                                        {{-- Sửa --}}
                                        <a href="{{ route('admin.promocodes.edit', $p->promo_code) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>

                                        {{-- Tắt/Bật --}}
                                        @if($p->status == 1)
                                            <form action="{{ route('admin.promocodes.deactivate', $p->promo_code) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Bạn chắc chắn muốn TẮT mã giảm giá này?')" 
                                                        class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                    <i class="fas fa-power-off"></i> Tắt
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.promocodes.activate', $p->promo_code) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Bạn chắc chắn muốn BẬT lại mã giảm giá này?')" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs transition flex items-center gap-1">
                                                    <i class="fas fa-lightbulb"></i> Bật lại
                                                </button>
                                            </form>
                                        @endif
                                        
                                        {{-- XÓA VĨNH VIỄN (ĐÃ BỔ SUNG) --}}
                                        <form action="{{ route('admin.promocodes.destroy', $p->promo_code) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('CẢNH BÁO: Bạn chắc chắn muốn XÓA VĨNH VIỄN mã giảm giá {{ $p->promo_code }}? Hành động này không thể hoàn tác!')" 
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

            {{-- PAGINATION --}}
            @if($promocodes->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 p-4">
                        {{ $promocodes->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 py-20 text-center">
                <i class="fas fa-tag text-6xl text-purple-400 opacity-40 mb-4"></i>
                <p class="text-xl font-black text-purple-300">Không tìm thấy Mã Giảm Giá nào</p>
                <a href="{{ route('admin.promocodes.create') }}" class="inline-block mt-6 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-3 px-6 rounded-xl shadow-xl text-base transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Thêm Mã Giảm Giá đầu tiên
                </a>
            </div>
        @endif

    </div>
</div>

{{-- STYLE CÙNG VỚI CÁC TRANG QUẢN LÝ KHÁC --}}
<style>
    /* Không cần thêm CSS tùy chỉnh vì tất cả đã được xử lý bằng Tailwind class, trừ khi bạn có yêu cầu cụ thể khác */
</style>
@endsection