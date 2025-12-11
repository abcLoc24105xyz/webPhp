@extends('admin.layouts.app')
@section('title', 'Thêm Mã Giảm Giá Mới')

@section('content')
{{-- Thay đổi nền chính (Đồng bộ với style dark/purple) --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-2">
                THÊM MÃ GIẢM GIÁ MỚI
            </h1>
            <p class="text-purple-300 text-lg">Thiết lập các quy tắc áp dụng mã giảm giá.</p>
        </div>

        {{-- FORM CONTAINER --}}
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl p-6 sm:p-10 border border-white/20">

            {{-- Hiển thị lỗi chung (nếu có) --}}
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Vui lòng kiểm tra lại các trường bị lỗi.
                </div>
            @endif

            <form action="{{ route('admin.promocodes.store') }}" method="POST">
                @csrf

                {{-- Group 1: Mã & Loại giảm giá --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Mã giảm giá <span class="text-red-400">*</span></label>
                        <input type="text" name="promo_code" value="{{ old('promo_code') }}" maxlength="20"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition"
                                placeholder="VD: XMAS2025" required>
                        @error('promo_code')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Loại giảm giá <span class="text-red-400">*</span></label>
                        <select name="discount_type" 
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white focus:ring-pink-500 focus:border-pink-500 transition" 
                                required>
                            <option value="1" class="bg-slate-700 text-white" {{ old('discount_type') == 1 ? 'selected' : '' }}>Giảm theo %</option>
                            <option value="2" class="bg-slate-700 text-white" {{ old('discount_type') == 2 ? 'selected' : '' }}>Giảm cố định (₫)</option>
                        </select>
                        @error('discount_type')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 2: Giá trị giảm & Đơn hàng tối thiểu --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Giá trị giảm <span class="text-red-400">*</span></label>
                        <input type="number" name="discount_value" value="{{ old('discount_value') }}" min="0" step="1"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition" required>
                        @error('discount_value')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Đơn hàng tối thiểu (để áp dụng)</label>
                        <input type="number" name="min_order_value" value="{{ old('min_order_value') }}" min="0"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition" placeholder="VD: 200000">
                        @error('min_order_value')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 3: Ngày bắt đầu & Ngày kết thúc --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Ngày bắt đầu <span class="text-red-400">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition" required>
                        @error('start_date')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Ngày kết thúc <span class="text-red-400">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition" required>
                        @error('end_date')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 4: Giới hạn dùng & Trạng thái --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Giới hạn số lần dùng (để trống = không giới hạn)</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition" placeholder="VD: 500">
                        @error('usage_limit')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Trạng thái <span class="text-red-400">*</span></label>
                        <div class="flex items-center space-x-8 mt-3">
                            <label class="flex items-center cursor-pointer">
                                {{-- Mặc định chọn Hoạt động (1) --}}
                                <input type="radio" name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }} 
                                       class="w-5 h-5 text-green-500 bg-transparent border-green-500 focus:ring-green-500">
                                <span class="ml-3 text-green-400 font-medium">1 - Hoạt động</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="0" {{ old('status') == 0 ? 'checked' : '' }} 
                                       class="w-5 h-5 text-gray-400 bg-transparent border-gray-400 focus:ring-gray-400">
                                <span class="ml-3 text-gray-400">0 - Tắt</span>
                            </label>
                        </div>
                        @error('status')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 5: Mô tả --}}
                <div class="mb-8">
                    <label class="block text-purple-300 font-bold mb-2 text-sm">Mô tả chi tiết (tùy chọn)</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                </div>


                {{-- Footer Actions --}}
                <div class="flex justify-between items-center mt-10 border-t border-white/20 pt-6">
                    <a href="{{ route('admin.promocodes.index') }}" 
                       class="px-6 py-2 bg-white/20 text-purple-300 font-medium rounded-xl hover:bg-white/30 transition flex items-center gap-2 transform hover:scale-105">
                        <i class="fas fa-arrow-left"></i> HỦY BỎ
                    </a>
                    
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-3 px-8 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-plus"></i> LƯU MÃ GIẢM GIÁ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection