{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Quản Lý Khách Hàng')

@section('content')
{{-- Đã sửa: Loại bỏ bg-white và shadow-2xl để sử dụng nền chung của layout, chỉ giữ lại padding --}}
<div class="p-6"> 
    {{-- Thanh tiêu đề và nút hành động --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-b pb-4">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <i class="fas fa-users mr-2 text-indigo-600"></i> Quản Lý Khách Hàng
            </h1>
            <div class="flex items-center space-x-4">
                {{-- Thêm nút Thêm Khách Hàng (nếu cần) --}}
                {{-- <a href="{{ route('admin.customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md">
                    <i class="fas fa-plus mr-1"></i> Thêm Khách Hàng
                </a> --}}
                <div class="text-sm text-gray-600 bg-gray-100 px-4 py-2 rounded-lg">
                    Tổng: <strong class="text-lg text-indigo-600">{{ $customers->total() }}</strong> tài khoản
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bảng danh sách --}}
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ tên & Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng ký</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $user)
                <tr class="hover:bg-indigo-50/30 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->user_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">{{ $user->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($user->status == 1)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                Hoạt động
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                Đã khóa
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        @if($user->status == 1)
                            <form action="{{ route('admin.customers.block', $user) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn KHÓA tài khoản của {{ $user->full_name }} không? Khách hàng sẽ không thể đăng nhập.')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 transition duration-150 p-2 rounded-md hover:bg-red-100" title="Khóa tài khoản">
                                    <i class="fas fa-lock"></i> Khóa
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.customers.unblock', $user) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn MỞ KHÓA tài khoản của {{ $user->full_name }} không?')">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 transition duration-150 p-2 rounded-md hover:bg-green-100" title="Mở khóa tài khoản">
                                    <i class="fas fa-unlock"></i> Mở khóa
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-lg text-gray-500 bg-gray-50">
                        <i class="fas fa-exclamation-circle mr-2"></i> Chưa có khách hàng nào được đăng ký trong hệ thống.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-8">
        {{ $customers->links() }}
    </div>
</div>
@endsection