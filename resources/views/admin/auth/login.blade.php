<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - GhienCine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('ticket.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-purple-900 via-black to-pink-900 flex items-center justify-center p-6">

    {{-- THU HẸP WIDTH TỐI ĐA --}}
    <div class="w-full max-w-sm"> 
        
        {{-- HEADER --}}
        <div class="text-center mb-10"> {{-- Giảm mb --}}
            {{-- Giảm font size và bỏ animate-pulse --}}
            <h1 class="text-5xl font-black bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent"> 
                GHIENCINE
            </h1>
            {{-- Giảm font size --}}
            <p class="text-xl text-purple-300 font-bold mt-2 tracking-wider">ADMIN PANEL</p> 
            <p class="text-purple-200 text-sm mt-1">Đăng nhập để quản trị hệ thống</p> {{-- Giảm font size --}}
        </div>

        {{-- KHUNG ĐĂNG NHẬP (GLASSMORPHISM EFFECT) --}}
        <div class="bg-white/10 backdrop-blur-2xl rounded-xl shadow-2xl p-8 border border-white/20"> {{-- Giảm padding và bo góc --}}
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                @if (session('success'))
                    <div class="bg-green-600/30 border border-green-500 text-green-200 px-4 py-3 rounded-xl text-center font-semibold mb-5"> {{-- Giảm padding --}}
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error') || $errors->any())
                    <div class="bg-red-600/30 border border-red-500 text-red-200 px-4 py-3 rounded-xl text-center font-semibold mb-5"> {{-- Giảm padding --}}
                        {{ session('error') ?? $errors->first() }}
                    </div>
                @endif

                <div class="space-y-6"> {{-- Giảm space-y --}}
                    
                    {{-- INPUT EMAIL --}}
                    <input type="email" name="email" required autofocus 
                           value="{{ old('email') }}"
                           placeholder="Email quản trị viên"
                           class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-purple-200 text-base focus:outline-none focus:ring-2 focus:ring-purple-400 transition"> {{-- Giảm padding, text size, focus ring --}}

                    {{-- INPUT PASSWORD --}}
                    <input type="password" name="password" required 
                           placeholder="Mật khẩu"
                           class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-purple-200 text-base focus:outline-none focus:ring-2 focus:ring-purple-400 transition"> {{-- Giảm padding, text size, focus ring --}}

                    {{-- NÚT ĐĂNG NHẬP --}}
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-black text-xl py-4 rounded-xl shadow-xl transform hover:scale-[1.01] transition-all duration-300 flex items-center justify-center gap-3"> {{-- Giảm padding, font size, shadow --}}
                        <i class="fas fa-lock text-lg"></i>
                        ĐĂNG NHẬP
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 text-purple-300 text-xs"> {{-- Giảm margin top và font size --}}
                <p>© {{ date('Y') }} GhienCine. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>