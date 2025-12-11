{{-- resources/views/admin/shows/create.blade.php – ĐỒNG BỘ HOÀN HẢO VỚI EDIT (THEO ẢNH BẠN GỬI) --}}
@extends('admin.layouts.app')

@section('title', 'Thêm suất chiếu mới')

@section('content')
<style>
    .modern-input[type="date"]::-webkit-calendar-picker-indicator,
    .modern-input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(1) sepia(1) saturate(5) hue-rotate(250deg) brightness(1.5);
        opacity: 1;
        cursor: pointer;
    }
    .modern-input[type="date"]::-webkit-datetime-edit-fields-wrapper,
    .modern-input[type="time"]::-webkit-datetime-edit-fields-wrapper {
        color: white;
    }
</style>

<div class="max-w-4xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
            <i class="fas fa-plus-circle mr-2"></i> Thêm suất chiếu mới
        </h1>
        <a href="{{ route('admin.shows.index') }}" 
           class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold hover:bg-gray-600 transition duration-200 shadow-md text-sm">
            ← Quay lại
        </a>
    </div>

    {{-- IMPORT OPTIONS --}}
    <div class="bg-blue-600/20 border border-blue-600 text-blue-200 px-6 py-3 rounded-lg mb-6 text-sm font-medium flex items-center justify-between shadow-md">
        <p class="mb-0 flex items-center gap-2">
            <i class="fas fa-file-import text-lg"></i> 
            <strong>Hoặc import nhiều suất chiếu cùng lúc:</strong>
        </p>
        <div>
            <a href="{{ route('admin.shows.import.form') }}" class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-sm font-bold transition ml-2 shadow-lg">
                <i class="fas fa-file-excel"></i> Import Excel
            </a>
            <a href="{{ route('admin.shows.import.template') }}" class="inline-flex items-center gap-1 border border-white/40 hover:border-white text-white px-3 py-1.5 rounded-md text-sm font-bold transition ml-2">
                Tải file mẫu
            </a>
        </div>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-gray-800 p-8 rounded-3xl shadow-2xl border border-gray-700">
        <form action="{{ route('admin.shows.store') }}" method="POST" id="showForm">
            @csrf

            {{-- ERROR ALERT --}}
            @if($errors->any())
                <div class="bg-red-900/40 border border-red-600 text-red-300 px-4 py-3 rounded-xl mb-6 shadow-md">
                    <strong class="block mb-2">Lỗi: Vui lòng kiểm tra lại thông tin.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="ml-4 list-disc text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- PHIM --}}
                <div class="mb-3">
                    <label for="movie_id" class="block text-sm font-medium text-purple-300 mb-2">Phim</label>
                    <select name="movie_id" id="movie_id" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                        <option value="">-- Chọn phim --</option>
                        @foreach($movies ?? [] as $id => $title)
                            <option value="{{ $id }}" {{ old('movie_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- RẠP --}}
                <div class="mb-3">
                    <label for="cinema_id" class="block text-sm font-medium text-purple-300 mb-2">Rạp</label>
                    <select name="cinema_id" id="cinema_id" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                        <option value="">-- Chọn rạp --</option>
                        @foreach($cinemas ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ old('cinema_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PHÒNG CHIẾU --}}
            <div class="mb-6">
                <label for="room_code" class="block text-sm font-medium text-purple-300 mb-2">Phòng chiếu</label>
                <select name="room_code" id="room_code" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                    <option value="">-- Chọn rạp trước --</option>
                </select>
                <small id="room-loading-status" class="text-pink-400 mt-2 block hidden"></small>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- NGÀY CHIẾU --}}
                <div class="mb-3">
                    <label for="show_date" class="block text-sm font-medium text-purple-300 mb-2">Ngày chiếu</label>
                    <input type="date" name="show_date" id="show_date" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" 
                           value="{{ old('show_date', now()->format('Y-m-d')) }}" required>
                </div>

                {{-- GIỜ BẮT ĐẦU --}}
                <div class="mb-3">
                    <label for="start_time" class="block text-sm font-medium text-purple-300 mb-2">Giờ bắt đầu</label>
                    <input type="time" id="start_time" name="start_time" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                </div>

                {{-- GIỜ KẾT THÚC --}}
                <div class="mb-3">
                    <label for="end_time" class="block text-sm font-medium text-purple-300 mb-2">Giờ kết thúc</label>
                    <input type="time" id="end_time" name="end_time" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" readonly>
                    <small class="text-gray-400 mt-1 block">Tự động tính từ thời lượng phim</small>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" 
                        class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-amber-500/50 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Tạo suất chiếu
                </button>
                <a href="{{ route('admin.shows.index') }}" 
                   class="bg-gray-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-gray-500 transition shadow-lg">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateEndTime() {
        const startTime = document.getElementById('start_time').value;
        const movieId = document.getElementById('movie_id').value;
        
        if (!startTime || !movieId) {
            document.getElementById('end_time').value = '';
            return;
        }
        
        document.getElementById('room-loading-status').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang tính giờ kết thúc...';
        document.getElementById('room-loading-status').classList.remove('hidden');

        fetch(`/admin/movies/${movieId}`)
            .then(res => res.json())
            .then(data => {
                if (data.duration) {
                    const [h, m] = startTime.split(':').map(Number);
                    const total = h * 60 + m + data.duration;
                    const eh = Math.floor(total / 60) % 24;
                    const em = total % 60;
                    document.getElementById('end_time').value = `${String(eh).padStart(2,'0')}:${String(em).padStart(2,'0')}`;
                }
            })
            .finally(() => {
                document.getElementById('room-loading-status').classList.add('hidden');
            });
    }

    document.getElementById('cinema_id').addEventListener('change', function () {
        const cinema_id = this.value;
        const roomSelect = document.getElementById('room_code');
        const statusDiv = document.getElementById('room-loading-status');

        if (!cinema_id) {
            roomSelect.innerHTML = '<option value="">-- Chọn rạp trước --</option>';
            return;
        }

        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang tải phòng...';
        statusDiv.classList.remove('hidden');
        roomSelect.innerHTML = '<option>-- Đang tải... --</option>';

        fetch('/admin/cinemas/' + cinema_id + '/rooms')
            .then(res => res.json())
            .then(data => {
                roomSelect.innerHTML = '<option value="">-- Chọn phòng --</option>';
                Object.entries(data).forEach(([code, name]) => {
                    const selected = "{{ old('room_code') }}" === code ? 'selected' : '';
                    roomSelect.innerHTML += `<option value="${code}" ${selected}>${name}</option>`;
                });
                statusDiv.classList.add('hidden');
            });
    });

    document.getElementById('start_time').addEventListener('change', calculateEndTime);
    document.getElementById('movie_id').addEventListener('change', calculateEndTime);

    // Khôi phục trạng thái khi có lỗi
    document.addEventListener('DOMContentLoaded', () => {
        const movieId = document.getElementById('movie_id').value;
        const cinemaId = document.getElementById('cinema_id').value;
        if (movieId) calculateEndTime();
        if (cinemaId) document.getElementById('cinema_id').dispatchEvent(new Event('change'));
    });
</script>
@endsection