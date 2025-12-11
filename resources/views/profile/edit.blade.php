@extends('layouts.app')
@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-600 to-pink-600 py-16 flex items-center justify-center">
    <div class="max-w-2xl w-full mx-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-12 text-center">
                <h1 class="text-4xl font-bold">Chỉnh sửa thông tin</h1>
                <p class="mt-2 opacity-90">Cập nhật hồ sơ cá nhân của bạn</p>
            </div>

            <!-- Body -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-6 text-center font-medium flex items-center justify-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Avatar -->
                    <div class="text-center mb-10">
                        <label class="block text-lg font-medium text-gray-700 mb-4">Ảnh đại diện</label>
                        <div class="w-40 h-40 mx-auto bg-white rounded-full overflow-hidden shadow-2xl border-8 border-white">
                            @if(Auth::user()->ava)
                                <img src="{{ asset('storage/' . Auth::user()->ava) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-7xl font-bold text-purple-600 bg-gray-200">
                                    {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->email, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="ava" accept="image/*"
                               class="mt-6 block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                        @error('ava')
                            <small class="text-red-600 block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Họ tên -->
                    <div class="mb-6">
                        <label class="block text-lg font-medium text-gray-700 mb-2">Họ và tên</label>
                        <input type="text" name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-600 transition text-lg">
                        @error('full_name')
                            <small class="text-red-600 block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-8">
                        <label class="block text-lg font-medium text-gray-700 mb-2">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-600 transition text-lg">
                        @error('phone')
                            <small class="text-red-600 block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-5 rounded-2xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 text-xl">
                            Cập nhật thông tin
                        </button>
                        <a href="{{ route('profile.show') }}"
                           class="flex-1 text-center bg-gray-200 text-gray-800 font-bold py-5 rounded-2xl hover:bg-gray-300 transition duration-300 text-xl">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection