@extends('admin.layouts.app')

@section('title', 'Sửa suất chiếu')

@section('content')
{{-- Thêm style cho input date/time trên nền tối (nếu cần) --}}
<style>
    .modern-input[type="date"]::-webkit-calendar-picker-indicator,
    .modern-input[type="time"]::-webkit-calendar-picker-indicator {
        /* Lật màu icon mặc định để hiện rõ trên nền tối */
        filter: invert(1) sepia(1) saturate(5) hue-rotate(250deg) brightness(1.5);
        opacity: 1; 
        cursor: pointer;
    }
    .modern-input[type="date"]::-webkit-datetime-edit-month-field, 
    .modern-input[type="date"]::-webkit-datetime-edit-day-field, 
    .modern-input[type="date"]::-webkit-datetime-edit-year-field,
    .modern-input[type="time"]::-webkit-datetime-edit-hour-field,
    .modern-input[type="time"]::-webkit-datetime-edit-minute-field {
        color: white; 
    }
</style>

<div class="max-w-4xl mx-auto py-6 sm:py-10">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
            <i class="fas fa-edit mr-2"></i> Sửa suất chiếu: {{ $show->show_id }}
        </h1>
        <a href="{{ route('admin.shows.index') }}" 
           class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold hover:bg-gray-600 transition duration-200 shadow-md text-sm">
            ← Quay lại
        </a>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-gray-800 p-8 rounded-3xl shadow-2xl border border-gray-700">
        <form action="{{ route('admin.shows.update', $show->show_id) }}" method="POST" id="editShowForm">
            @csrf @method('PUT')
            
            {{-- THÔNG BÁO LỖI (Nếu cần) --}}
            @if ($errors->any())
                <div class="bg-red-900/40 border border-red-600 text-red-300 px-4 py-3 rounded-xl mb-6 shadow-md">
                    <strong class="block mb-2">Lỗi: Vui lòng kiểm tra lại thông tin.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
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
                        @foreach($movies as $id => $title)
                            <option value="{{ $id }}" {{ $show->movie_id == $id ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- RẠP --}}
                <div class="mb-3">
                    <label for="cinema_id" class="block text-sm font-medium text-purple-300 mb-2">Rạp</label>
                    <select name="cinema_id" id="cinema_id" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                        @foreach($cinemas as $id => $name)
                            <option value="{{ $id }}" {{ $show->cinema_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            {{-- PHÒNG CHIẾU --}}
            <div class="mb-6">
                <label for="room_code" class="block text-sm font-medium text-purple-300 mb-2">Phòng chiếu</label>
                <select name="room_code" id="room_code" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" required>
                    @foreach($rooms as $code => $name)
                        <option value="{{ $code }}" {{ $show->room_code == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <small id="room-loading-status" class="text-pink-400 mt-2 block hidden"></small>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- NGÀY CHIẾU --}}
                <div class="mb-3">
                    <label for="show_date" class="block text-sm font-medium text-purple-300 mb-2">Ngày chiếu</label>
                    <input type="date" name="show_date" id="show_date" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" 
                           value="{{ $show->show_date->format('Y-m-d') }}" required>
                </div>
                
                {{-- GIỜ BẮT ĐẦU --}}
                <div class="mb-3">
                    <label for="start_time" class="block text-sm font-medium text-purple-300 mb-2">Giờ bắt đầu</label>
                    <input type="time" id="start_time" name="start_time" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" 
                           value="{{ substr($show->start_time, 0, 5) }}" required>
                </div>
                
                {{-- GIỜ KẾT THÚC --}}
                <div class="mb-3">
                    <label for="end_time" class="block text-sm font-medium text-purple-300 mb-2">Giờ kết thúc</label>
                    <input type="time" id="end_time" name="end_time" class="modern-input w-full bg-gray-700 border border-gray-600 text-white p-3 rounded-xl focus:ring-pink-500 focus:border-pink-500 transition duration-150" 
                           value="{{ substr($show->end_time, 0, 5) }}" readonly>
                    <small class="text-gray-400 mt-1 block">Tự động tính từ thời lượng phim</small>
                </div>
            </div>
            
            {{-- ACTIONS --}}
            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" 
                        class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-amber-500/50 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Cập nhật
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
// Hàm tính giờ kết thúc
    function calculateEndTime() {
        const startTime = document.getElementById('start_time').value;
        const movieId = document.getElementById('movie_id').value;
        
        if (!startTime || !movieId) return;
        
        // Hiển thị trạng thái đang tải
        document.getElementById('room-loading-status').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang tính giờ kết thúc...';
        document.getElementById('room-loading-status').classList.remove('hidden');

        // Fetch thông tin phim để lấy duration
        // Giả định: API trả về { duration: number }
        fetch(`/admin/movies/${movieId}`)
            .then(res => {
                if (!res.ok) throw new Error('Failed to fetch movie data');
                return res.json();
            })
            .then(data => {
                if (data.duration) {
                    const [hours, minutes] = startTime.split(':').map(Number);
                    // Thêm 15 phút giãn cách (Nếu logic của bạn có, nếu không thì chỉ là data.duration)
                    // Giữ logic gốc của bạn: totalMinutes = hours * 60 + minutes + data.duration
                    const totalMinutes = hours * 60 + minutes + data.duration;
                    
                    const endHours = Math.floor(totalMinutes / 60) % 24;
                    const endMinutes = totalMinutes % 60;
                    
                    const endTime = `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;
                    document.getElementById('end_time').value = endTime;
                }
            })
            .catch(err => console.error('Lỗi lấy thông tin phim:', err))
            .finally(() => {
                // Ẩn trạng thái tải sau khi hoàn thành
                document.getElementById('room-loading-status').classList.add('hidden');
            });
    }

    // Cập nhật rooms khi thay đổi rạp
    document.getElementById('cinema_id').addEventListener('change', function () {
        const cinema_id = this.value;
        const roomSelect = document.getElementById('room_code');
        const statusDiv = document.getElementById('room-loading-status');
        const currentRoomCode = "{{ $show->room_code }}"; // Giữ lại giá trị phòng hiện tại
        
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang tải phòng...';
        statusDiv.classList.remove('hidden');
        roomSelect.innerHTML = '<option value="">-- Đang tải phòng... --</option>';

        if (!cinema_id) {
            roomSelect.innerHTML = '<option value="">-- Chọn rạp --</option>';
            statusDiv.classList.add('hidden');
            return;
        }

        // Giả định endpoint API đã có sẵn
        fetch('/admin/cinemas/' + cinema_id + '/rooms')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                roomSelect.innerHTML = '<option value="">-- Chọn phòng --</option>';
                let roomsFound = false;
                Object.entries(data).forEach(([code, name]) => {
                    roomsFound = true;
                    // Nếu đang chỉnh sửa suất chiếu và rạp không thay đổi, giữ lại phòng hiện tại
                    const selected = code == currentRoomCode && cinema_id == "{{ $show->cinema_id }}" ? 'selected' : '';
                    roomSelect.innerHTML += `<option value="${code}" ${selected}>${name}</option>`;
                });
                
                if (!roomsFound) {
                    roomSelect.innerHTML = '<option value="">Không có phòng</option>';
                }

                // Nếu người dùng vừa chọn rạp khác rạp cũ, tự động chọn phòng đầu tiên
                if (cinema_id != "{{ $show->cinema_id }}") {
                    roomSelect.value = roomSelect.options[0].value;
                } else {
                    roomSelect.value = currentRoomCode;
                }
                
                statusDiv.classList.add('hidden');
            })
            .catch(err => {
                roomSelect.innerHTML = '<option value="">Lỗi tải phòng</option>';
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Lỗi tải phòng';
                console.error('Lỗi tải phòng chiếu:', err);
            });
    });

    // Gắn sự kiện (Giữ nguyên)
    document.getElementById('start_time').addEventListener('change', calculateEndTime);
    document.getElementById('movie_id').addEventListener('change', calculateEndTime);
    document.getElementById('cinema_id').addEventListener('change', function() {
        // Tránh gọi hai lần khi load trang. Chỉ gọi khi có sự kiện change.
        loadRoomsByCinema.call(this); // Gọi hàm tải phòng
    });

    // Tính end_time khi load trang (Giữ nguyên)
    document.addEventListener('DOMContentLoaded', function () {
        calculateEndTime();

    });
</script>
@endsection