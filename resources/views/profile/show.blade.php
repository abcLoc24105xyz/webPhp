@extends('layouts.app')
@section('title', 'Thông tin cá nhân')

@section('content')
<div class="min-h-screen bg-gray-100 py-16"> {{-- Đổi màu nền ngoài để đồng bộ với Lịch sử đặt vé --}}
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-12 text-center relative">
                
                <div class="w-32 h-32 mx-auto bg-white rounded-full overflow-hidden shadow-2xl border-6 border-white"> {{-- Giảm border xuống 6 --}}
                    @if(Auth::user()->ava)
                        <img src="{{ asset('storage/' . Auth::user()->ava) }}" alt="Avatar" class="w-full h-full object-cover">
                    @elseif(Auth::user()->provider_avatar)
                        <img src="{{ Auth::user()->provider_avatar }}" alt="Avatar Google" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl font-bold text-purple-600 bg-gray-200"> {{-- Giảm font size --}}
                            {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->email, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h1 class="text-3xl font-bold mt-4">{{ Auth::user()->full_name ?? 'Khách hàng' }}</h1> {{-- Giảm kích thước chữ --}}
                <p class="text-lg opacity-90 mt-1">{{ Auth::user()->email }}</p> {{-- Giảm kích thước chữ --}}

                @if(Auth::user()->provider && Auth::user()->provider === 'google')
                    <div class="mt-4 flex justify-center">
                        <p class="text-sm bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full inline-flex items-center gap-2 font-medium">
                            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-4 h-4">
                            Đăng nhập bằng Google – Không thể đổi mật khẩu
                        </p>
                    </div>
                @endif

                <div class="mt-6 flex justify-center">
                    <a href="{{ route('profile.edit') }}" 
                       class="inline-flex items-center bg-white text-purple-600 font-bold py-2.5 px-6 rounded-full shadow-lg hover:bg-purple-50 transition transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Chỉnh sửa hồ sơ
                    </a>
                </div>
            </div>

            <div class="p-10">
                <div class="grid md:grid-cols-2 gap-10"> {{-- Tăng gap để chia cột rõ ràng hơn --}}
                    
                    {{-- Cột 1: Thông tin tài khoản --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center border-b pb-2 border-gray-100"> {{-- Giảm font size, thêm border --}}
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Chi tiết tài khoản
                        </h3>
                        <div class="space-y-4 text-base"> {{-- Giảm font size và space-y --}}
                            <div class="flex justify-between py-2 border-b border-gray-100"> {{-- Giảm padding --}}
                                <span class="text-gray-600">Họ tên</span>
                                <span class="font-semibold text-gray-800">{{ Auth::user()->full_name ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Email</span>
                                <span class="font-semibold text-gray-800">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Số điện thoại</span>
                                <span class="font-semibold text-gray-800">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Tham gia từ</span>
                                <span class="font-semibold text-gray-800">{{ Auth::user()->created_at->translatedFormat('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Cột 2: Hành động nhanh --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center border-b pb-2 border-gray-100"> {{-- Giảm font size, thêm border --}}
                            <svg class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Hành động & Bảo mật
                        </h3>
                        <div class="space-y-4"> {{-- Giảm space-y --}}
                            
                            {{-- Lịch sử đặt vé --}}
                            <a href="{{ route('profile.history') }}" class="block bg-purple-100 text-purple-700 font-bold py-4 px-6 rounded-xl hover:bg-purple-200 transition text-center text-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5"> {{-- Giảm padding và shadow --}}
                                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2 3-.895 3-2-1.343-2-3-2zM4 16h16M4 16l1.242-1.242C5.64 13.784 6.828 13 8 13h8c1.172 0 2.36.784 2.758 1.758L20 16m-2-6a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Lịch sử đặt vé
                            </a>

                            {{-- Nút đổi mật khẩu chỉ hiện cho tài khoản thường --}}
                            @if(!Auth::user()->provider)
                                <a href="{{ route('password.change') }}" class="block bg-pink-100 text-pink-700 font-bold py-4 px-6 rounded-xl hover:bg-pink-200 transition text-center text-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5"> {{-- Giảm padding và shadow --}}
                                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v5.586a1 1 0 01-.293.707l-3.586 3.586a1 1 0 01-1.414 0l-3.586-3.586A1 1 0 017 14.586V9a2 2 0 012-2h6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V8m0 3v3"/>
                                    </svg>
                                    Đổi mật khẩu
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection