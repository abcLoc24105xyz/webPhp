{{-- resources/views/admin/shows/import.blade.php – ĐỒNG BỘ 100% VỚI CREATE/EDIT --}}
@extends('admin.layouts.app')

@section('title', 'Import suất chiếu')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
            <i class="fas fa-file-import mr-2"></i> Import suất chiếu từ Excel
        </h1>
        <a href="{{ route('admin.shows.index') }}" 
           class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold hover:bg-gray-600 transition duration-200 shadow-md text-sm">
            ← Quay lại
        </a>
    </div>

    {{-- SUCCESS / INFO / WARNING / ERROR --}}
    @if(session('success'))
        <div class="bg-green-900/40 border border-green-600 text-green-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('success') }}</p>
            @if(session('import_summary'))
                <div class="mt-4 p-4 bg-black/50 rounded-xl border border-green-500/50">
                    <h6 class="font-bold text-green-200 mb-3">Kết quả import:</h6>
                    <ul class="space-y-1 text-sm">
                        <li>Tổng dòng: <strong class="text-white">{{ session('import_summary')['total'] }}</strong></li>
                        <li>Tạo mới: <strong class="text-green-400">{{ session('import_summary')['success'] }}</strong></li>
                        <li>Cập nhật: <strong class="text-cyan-400">{{ session('import_summary')['updated'] }}</strong></li>
                        <li>Lỗi: <strong class="text-red-400">{{ session('import_summary')['failed'] }}</strong></li>
                        <li>Bỏ qua: <strong class="text-gray-400">{{ session('import_summary')['skipped'] }}</strong></li>
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-900/40 border border-blue-600 text-blue-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-info-circle"></i> {{ session('info') }}</p>
            @if(session('import_summary')) {{-- (giống success) --}} @endif
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-900/40 border border-yellow-600 text-yellow-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/40 border border-red-600 text-red-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-times-circle"></i> {{ session('error') }}</p>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-red-900/40 border border-red-600 text-red-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold mb-3"><i class="fas fa-exclamation-circle"></i> Có lỗi trong file:</p>
            <div class="max-h-64 overflow-y-auto bg-black/50 p-4 rounded-xl">
                <ul class="space-y-1 text-sm">
                    @foreach(session('import_errors') as $err)
                        <li class="flex items-start gap-2">
                            <i class="fas fa-times-circle mt-0.5"></i>
                            <span>{{ $err }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-900/40 border border-red-600 text-red-300 px-6 py-4 rounded-2xl mb-6 shadow-inner">
            <p class="font-bold mb-3"><i class="fas fa-times-circle"></i> Lỗi validation:</p>
            <ul class="space-y-1 text-sm ml-6">
                @foreach($errors->all() as $error)
                    <li class="list-disc">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD IMPORT --}}
    <div class="bg-gray-800 p-8 rounded-3xl shadow-2xl border border-gray-700">
        <form action="{{ route('admin.shows.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="mb-8">
                <label class="block text-purple-300 font-bold mb-4 text-lg">
                    <i class="fas fa-upload mr-2"></i> Chọn file Excel <span class="text-red-400">*</span>
                </label>

                <div class="relative border-2 border-dashed border-gray-600 rounded-2xl p-10 text-center hover:border-pink-500 transition">
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           onchange="onFileChange(this)">
                    <div class="text-gray-400">
                        <i class="fas fa-cloud-upload-alt text-6xl mb-4"></i>
                        <p class="text-lg font-bold">Kéo thả file vào đây hoặc click để chọn</p>
                        <p class="text-sm mt-2">Hỗ trợ: .xlsx, .xls | Tối đa 10MB</p>
                    </div>
                </div>

                <div id="filePreview" class="mt-4"></div>
                @error('excel_file')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- BẢNG MẪU --}}
            <div class="bg-gray-700/50 rounded-2xl p-6 mb-8 border border-gray-600">
                <h6 class="text-purple-300 font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-table"></i> Cấu trúc file mẫu
                </h6>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="bg-gray-700 text-purple-300">
                            <tr>
                                <th class="px-4 py-3">movie_title</th>
                                <th class="px-4 py-3">cinema_name</th>
                                <th class="px-4 py-3">room_code</th>
                                <th class="px-4 py-3">show_date</th>
                                <th class="px-4 py-3">start_time</th>
                                <th class="px-4 py-3">remaining_seats</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            <tr>
                                <td class="px-4 py-3">Bí Mật Của Gió</td>
                                <td class="px-4 py-3">Vincom Center</td>
                                <td class="px-4 py-3">R101</td>
                                <td class="px-4 py-3">15/12/2025</td>
                                <td class="px-4 py-3">14:00</td>
                                <td class="px-4 py-3">80</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-400 mt-4">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Tên phim & rạp phải <strong>khớp chính xác</strong> với dữ liệu hệ thống
                </p>
            </div>

            {{-- NÚT HÀNH ĐỘNG --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.shows.import.template') }}"
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-download"></i> Tải file mẫu
                </a>
                <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white font-black rounded-xl shadow-2xl transition transform hover:scale-105 flex items-center gap-3">
                    <i class="fas fa-file-import"></i> Import ngay
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL LOADING --}}
<div class="modal fade fixed inset-0 bg-black/70 flex items-center justify-center z-50" id="loadingModal" style="display:none">
    <div class="bg-gray-800 p-10 rounded-3xl shadow-2xl text-center">
        <div class="spinner-border text-yellow-500 w-16 h-16 mb-6"></div>
        <h4 class="text-xl font-bold text-white mb-2">Đang xử lý file...</h4>
        <p class="text-gray-400">Vui lòng chờ trong giây lát</p>
    </div>
</div>

<script>
function onFileChange(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');

    if (!file) {
        preview.innerHTML = '';
        return;
    }

    const ext = file.name.split('.').pop().toLowerCase();
    if (!['xlsx', 'xls'].includes(ext)) {
        preview.innerHTML = `<p class="text-red-400 text-sm mt-3"><i class="fas fa-times-circle"></i> Chỉ chấp nhận file .xlsx hoặc .xls</p>`;
        input.value = '';
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        preview.innerHTML = `<p class="text-red-400 text-sm mt-3"><i class="fas fa-times-circle"></i> File không được vượt quá 10MB</p>`;
        input.value = '';
        return;
    }

    preview.innerHTML = `
        <div class="mt-4 p-4 bg-green-900/30 border border-green-600 rounded-xl text-green-300 text-sm">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Đã chọn:</strong> ${file.name} 
            <span class="text-gray-400">(${(file.size/1024/1024).toFixed(2)} MB)</span>
        </div>`;
}

document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('excel_file');
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Vui lòng chọn file Excel!');
        return;
    }
    document.getElementById('loadingModal').style.display = 'flex';
});
</script>
@endsection