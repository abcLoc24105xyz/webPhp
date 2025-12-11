{{-- resources/views/admin/combos/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Thêm Combo mới')

@section('content')
{{-- Nền chính: Áp dụng nền tối từ index --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6 sm:p-8">

    {{-- Container Glassmorphism --}}
    <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 ring-1 ring-white/5">

        {{-- Header --}}
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-400 mb-8 flex items-center gap-3">
            <i class="fas fa-plus-circle"></i> THÊM COMBO MỚI
        </h1>

        <form action="{{ route('admin.combos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- 1. Tên Combo --}}
                <div class="filter-box">
                    <label class="filter-label">
                        <i class="fas fa-box"></i> Tên Combo <span class="text-pink-400">*</span>
                    </label>
                    <input type="text" name="combo_name" value="{{ old('combo_name') }}"
                            class="modern-input placeholder-white/50" required placeholder="VD: Combo Couple">
                    @error('combo_name')<p class="text-pink-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- 2. Giá --}}
                <div class="filter-box">
                    <label class="filter-label">
                        <i class="fas fa-money-bill-wave"></i> Giá (VNĐ) <span class="text-pink-400">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}"
                            class="modern-input placeholder-white/50" required placeholder="80000" min="0">
                    @error('price')<p class="text-pink-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- 3. Mô tả --}}
            <div class="mt-6 filter-box">
                <label class="filter-label">
                    <i class="fas fa-align-left"></i> Mô tả (hiển thị cho khách)
                </label>
                <textarea name="description" rows="4"
                            class="modern-input placeholder-white/50"
                            placeholder="VD: 2 ly nước ngọt + 1 bắp rang bơ lớn">{{ old('description') }}</textarea>
                @error('description')<p class="text-pink-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- 4. Hình ảnh --}}
            <div class="mt-8 border-t border-white/10 pt-8">
                <label class="block text-purple-300 font-bold mb-4 text-xl"><i class="fas fa-image mr-2"></i> Hình ảnh Combo (Tùy chọn)</label>

                <div class="filter-box">
                    <label class="filter-label">
                        <i class="fas fa-upload"></i> Chọn file ảnh (JPG, PNG, WEBP - Max 2MB)
                    </label>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-purple-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-pink-500/20 file:text-pink-200 hover:file:bg-pink-500/30 cursor-pointer">
                    @error('image')<p class="text-pink-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- 5. Trạng thái --}}
            <div class="mt-8 border-t border-white/10 pt-8">
                <label class="block text-purple-300 font-bold mb-4 text-xl"><i class="fas fa-toggle-on mr-2"></i> Trạng thái hiển thị</label>
                <div class="flex items-center space-x-10 bg-black/50 p-4 rounded-xl border border-white/10">

                    {{-- Đang hoạt động (Mặc định được chọn) --}}
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}
                                class="w-5 h-5 text-green-500 border-green-500 focus:ring-green-500 bg-gray-800 checked:bg-green-600">
                        <span class="ml-3 text-lg text-green-400 font-bold group-hover:text-green-300 transition">1 - Đang hoạt động (Hiển thị ngay)</span>
                    </label>

                    {{-- Đã ẩn --}}
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="status" value="0" {{ old('status', 1) == 0 ? 'checked' : '' }}
                                class="w-5 h-5 text-gray-400 border-gray-400 focus:ring-gray-400 bg-gray-800 checked:bg-gray-600">
                        <span class="ml-3 text-lg text-gray-400 group-hover:text-gray-300 transition">0 - Tắt (Không hiển thị cho khách)</span>
                    </label>
                </div>
                @error('status')<p class="text-pink-400 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            {{-- Buttons --}}
            <div class="mt-10 flex justify-end space-x-4 border-t border-white/10 pt-6">
                <a href="{{ route('admin.combos.index') }}"
                   class="px-6 py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-800 transition transform hover:scale-[1.02]">
                    <i class="fas fa-undo mr-2"></i> Hủy bỏ
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black rounded-xl shadow-2xl transition transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-save"></i> LƯU COMBO MỚI
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Bổ sung lại các style CSS đã định nghĩa --}}
<style>
    /* Khối filter/input container */
    .filter-box {
        position: relative;
        background: rgba(0,0,0,0.7); /* Nền tối nhẹ cho input container */
        border-radius: 16px;
        padding: 16px;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Label */
    .filter-label {
        display: flex;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #d8b4fe; /* purple-300 */
        margin-bottom: 6px;
    }

    /* Input & Textarea */
    .modern-input {
        width: 100%;
        background: transparent;
        color: white;
        font-size: 16px;
        outline: none;
        border: none;
        padding: 0;
        line-height: 1.5;
    }
</style>
@endsection