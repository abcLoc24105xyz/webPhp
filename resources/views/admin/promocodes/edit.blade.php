@extends('admin.layouts.app')
@section('title', 'Sửa Mã Giảm Giá: '.$promocode->promo_code)

@section('content')
{{-- Thay đổi nền chính (Giả định layout gốc có nền tối) --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-2">
                CHỈNH SỬA MÃ GIẢM GIÁ
            </h1>
            <p class="text-purple-300 text-lg">Mã đang chỉnh sửa: <span class="font-extrabold text-pink-400">{{ $promocode->promo_code }}</span></p>
        </div>

        {{-- FORM CONTAINER --}}
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl p-6 sm:p-10 border border-white/20">

            {{-- SUCCESS/ERROR MESSAGE --}}
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-5 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-xl"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.promocodes.update', $promocode->promo_code) }}" method="POST">
                @csrf @method('PUT')

                {{-- Group 1: Mã & Loại giảm giá --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Mã giảm giá <span class="text-red-400">*</span></label>
                        <input type="text" name="promo_code" value="{{ old('promo_code', $promocode->promo_code) }}" maxlength="20"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition disabled:opacity-50" 
                                required>
                        @error('promo_code')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Loại giảm giá <span class="text-red-400">*</span></label>
                        <select name="discount_type" 
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white focus:ring-pink-500 focus:border-pink-500 transition" 
                                required>
                            <option value="1" class="bg-slate-700 text-white" {{ old('discount_type', $promocode->discount_type) == 1 ? 'selected' : '' }}>Giảm theo %</option>
                            <option value="2" class="bg-slate-700 text-white" {{ old('discount_type', $promocode->discount_type) == 2 ? 'selected' : '' }}>Giảm cố định (₫)</option>
                        </select>
                        @error('discount_type')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 2: Giá trị giảm & Đơn hàng tối thiểu --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Giá trị giảm <span class="text-red-400">*</span></label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $promocode->discount_value) }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">
                        @error('discount_value')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Đơn hàng tối thiểu</label>
                        <input type="number" name="min_order_value" value="{{ old('min_order_value', $promocode->min_order_value) }}"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">
                        @error('min_order_value')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 3: Ngày bắt đầu & Ngày kết thúc --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Ngày bắt đầu <span class="text-red-400">*</span></label>
                        {{-- Laravel/Blade sẽ cần format ngày cho input type="date" nếu giá trị là Carbon object --}}
                        <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($promocode->start_date)->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">
                        @error('start_date')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Ngày kết thúc <span class="text-red-400">*</span></label>
                         <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($promocode->end_date)->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">
                        @error('end_date')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Group 4: Giới hạn dùng & Trạng thái --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Giới hạn số lần dùng</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $promocode->usage_limit) }}"
                                class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">
                        <p class="text-purple-400/70 text-xs mt-1">Để trống nếu không giới hạn (∞).</p>
                        @error('usage_limit')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-purple-300 font-bold mb-2 text-sm">Trạng thái <span class="text-red-400">*</span></label>
                        <div class="flex items-center space-x-8 mt-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="1" {{ old('status', $promocode->status) == 1 ? 'checked' : '' }} 
                                       class="w-5 h-5 text-green-500 bg-transparent border-green-500 focus:ring-green-500">
                                <span class="ml-3 text-green-400 font-medium">1 - Hoạt động</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="0" {{ old('status', $promocode->status) == 0 ? 'checked' : '' }} 
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
                        class="w-full px-4 py-3 bg-white/5 border border-purple-500/50 rounded-xl text-white placeholder-purple-400 focus:ring-pink-500 focus:border-pink-500 transition">{{ old('description', $promocode->description) }}</textarea>
                    @error('description')<p class="text-red-400 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                </div>


                {{-- Footer Actions --}}
                <div class="flex justify-between items-center mt-10 border-t border-white/20 pt-6">
                    <a href="{{ route('admin.promocodes.index') }}" 
                       class="px-6 py-2 bg-white/20 text-purple-300 font-medium rounded-xl hover:bg-white/30 transition flex items-center gap-2 transform hover:scale-105">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    
                    <button type="submit" class="bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-black py-3 px-8 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-save"></i> CẬP NHẬT MÃ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection