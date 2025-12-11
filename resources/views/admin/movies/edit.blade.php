{{-- resources/views/admin/movies/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Sửa Phim')
@section('subtitle', 'Cập nhật thông tin chi tiết của phim.')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-1">
                    SỬA PHIM
                </h1>
                <p class="text-purple-300 text-lg font-medium">{{ $movie->title }}</p>
                <div class="h-0.5 w-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mt-2"></div>
            </div>

            <a href="{{ route('admin.movies.index') }}"
               class="px-6 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-bold transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 p-5 rounded-lg bg-green-500/20 border border-green-500 text-green-200 flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="mb-6 p-5 rounded-lg bg-red-500/20 border-2 border-red-500 text-red-200">
                <p class="font-bold mb-3 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:
                </p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.movies.update', $movie->movie_id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- CARD 1: Thông tin cơ bản --}}
            <div class="bg-black/40 backdrop-blur-md rounded-xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600/60 to-pink-600/60">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i> Thông tin cơ bản
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Tên phim <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $movie->title) }}" required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                               placeholder="Nhập tên phim">
                        @error('title') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Danh mục <span class="text-red-400">*</span></label>
                        <select name="cate_id" required class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition pr-10"
                                style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27%23a78bfa%27 viewBox=%270 0 24 24%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->cate_id }}" {{ old('cate_id', $movie->cate_id) == $cat->cate_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cate_id') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Đạo diễn <span class="text-red-400">*</span></label>
                            <input type="text" name="director" value="{{ old('director', $movie->director) }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            @error('director') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Thời lượng (phút) <span class="text-red-400">*</span></label>
                            <input type="number" name="duration" value="{{ old('duration', $movie->duration) }}" required min="30" max="300"
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            @error('duration') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                Ngày chiếu sớm <span class="text-yellow-400 text-xs">(Tùy chọn)</span>
                            </label>
                            <input type="date" name="early_premiere_date"
                                   value="{{ old('early_premiere_date', $movie->early_premiere_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            <p class="text-purple-300 text-xs mt-1">Nếu có buổi chiếu sớm, hệ thống sẽ bắt đầu từ ngày này</p>
                            @error('early_premiere_date') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Ngày công chiếu chính thức <span class="text-red-400">*</span></label>
                            <input type="date" name="release_date"
                                   value="{{ old('release_date', $movie->release_date?->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            @error('release_date') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Phân loại & Trạng thái (TỰ ĐỘNG) --}}
            <div class="bg-black/40 backdrop-blur-md rounded-xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600/60 to-pink-600/60">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-tags"></i> Phân loại & Trạng thái
                    </h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Giới hạn tuổi <span class="text-red-400">*</span></label>
                        <select name="age_limit" required class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition pr-10"
                                style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27%23a78bfa%27 viewBox=%270 0 24 24%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;">
                            <option value="">-- Chọn giới hạn tuổi --</option>
                            <option value="0" {{ old('age_limit', $movie->age_limit) == 0 ? 'selected' : '' }}>P - Phổ thông (mọi lứa tuổi)</option>
                            <option value="13" {{ old('age_limit', $movie->age_limit) == 13 ? 'selected' : '' }}>T13 - Cấm trẻ em dưới 13 tuổi</option>
                            <option value="16" {{ old('age_limit', $movie->age_limit) == 16 ? 'selected' : '' }}>T16 - Cấm trẻ em dưới 16 tuổi</option>
                            <option value="18" {{ old('age_limit', $movie->age_limit) == 18 ? 'selected' : '' }}>T18 - Cấm trẻ em dưới 18 tuổi</option>
                        </select>
                        @error('age_limit') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gradient-to-br from-blue-600/20 to-purple-600/20 border border-blue-500/40 rounded-xl p-5">
                        <p class="text-blue-200 font-bold mb-3 flex items-center gap-2">
                            <i class="fas fa-robot text-xl"></i> Trạng thái tự động
                        </p>
                        <div class="text-2xl font-black mb-2">
                            @if($movie->status == 1)
                                <span class="text-cyan-400">Sắp chiếu</span>
                            @elseif($movie->status == 2)
                                <span class="text-green-400">Đang chiếu</span>
                            @else
                                <span class="text-red-400">Đã kết thúc</span>
                            @endif
                        </div>
                        <p class="text-blue-300 text-xs leading-relaxed">
                            Hệ thống sẽ <strong>tự động cập nhật trạng thái</strong> sau khi bạn lưu,<br>
                            dựa trên ngày chiếu sớm / ngày công chiếu.
                        </p>
                    </div>
                </div>
            </div>

            {{-- CARD 3: Media & Mô tả --}}
            <div class="bg-black/40 backdrop-blur-md rounded-xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600/60 to-pink-600/60">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-photo-video"></i> Poster & Mô tả
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Poster hiện tại</label>
                            @if($movie->poster)
                                <img src="{{ asset('poster/' . $movie->poster) }}" alt="{{ $movie->title }}"
                                     class="w-full max-w-xs rounded-lg shadow-lg border border-white/20">
                            @else
                                <div class="bg-gray-800 border-2 border-dashed border-gray-600 rounded-lg w-full h-64 flex items-center justify-center text-gray-500">
                                    Chưa có poster
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Thay poster mới (tùy chọn)</label>
                            <input type="file" name="poster" accept="image/*"
                                   class="w-full px-6 py-8 rounded-xl bg-gradient-to-br from-purple-600/20 to-pink-600/20 border-2 border-dashed border-purple-400 text-white cursor-pointer hover:border-purple-300 hover:bg-purple-600/30 transition file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:font-bold hover:file:bg-purple-700">
                            <p class="text-purple-300 text-xs mt-2">JPG, PNG, WebP • Tối đa 2MB</p>
                            @error('poster') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Trailer YouTube (tùy chọn)</label>
                        <input type="url" name="trailer" value="{{ old('trailer', $movie->trailer) }}"
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('trailer') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Mô tả phim <span class="text-red-400">*</span></label>
                        <textarea name="description" rows="6" required
                                  class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition resize-none"
                                  placeholder="Nhập nội dung giới thiệu phim...">{{ old('description', $movie->description) }}</textarea>
                        @error('description') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex flex-col sm:flex-row gap-6 justify-center pt-10">
                <a href="{{ route('admin.movies.index') }}"
                   class="px-10 py-4 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-bold text-lg transition transform hover:scale-105 flex items-center justify-center gap-3">
                    <i class="fas fa-times-circle"></i> Hủy bỏ
                </a>

                <button type="submit"
                        class="px-12 py-4 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black text-lg shadow-2xl transition transform hover:scale-105 flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> CẬP NHẬT PHIM
                </button>
            </div>
        </form>
    </div>
</div>

{{-- CUSTOM STYLES --}}
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(2);
        cursor: pointer;
    }
    select option {
        background: #1a1a2e !important;
        color: white !important;
    }
</style>
@endsection