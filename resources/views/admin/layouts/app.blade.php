<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - @yield('title', 'GhienCine')</title> {{-- Sửa Title --}}

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="{{ asset('ticket.ico') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Roboto', sans-serif; }
        .nav-link-active {
            background: linear-gradient(90deg, #9333ea, #db2777) !important;
            box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4) !important;
            color: white !important;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gradient-to-br from-indigo-950 via-black to-pink-950 text-white">

<div class="flex h-screen">

    <aside class="w-72 bg-black/60 backdrop-blur-3xl border-r border-white/10 p-6 flex flex-col">

        <div class="mb-10 pt-2">
            <h1 class="text-4xl font-black bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                GHIENCINE
            </h1> {{-- Sửa tên thương hiệu --}}
            <p class="text-gray-400 text-sm mt-1">Hệ thống Quản lý Admin</p>
        </div>

        <nav class="space-y-2 flex-1">

            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
                    ['route' => 'admin.movies.index', 'icon' => 'fas fa-film', 'label' => 'Quản lý Phim', 'match' => 'admin.movies.*'],
                    ['route' => 'admin.shows.index', 'icon' => 'fas fa-calendar-alt', 'label' => 'Suất Chiếu', 'match' => 'admin.shows.*'],
                    ['route' => 'admin.combos.index', 'icon' => 'fas fa-glass-whiskey', 'label' => 'Quản lý Combo', 'match' => 'admin.combos.*'],
                    ['route' => 'admin.promocodes.index', 'icon' => 'fas fa-tag', 'label' => 'Mã Giảm Giá', 'match' => 'admin.promocodes.*'],
                    ['route' => 'admin.revenue.index', 'icon' => 'fas fa-chart-line', 'label' => 'Doanh Thu'],
                    ['route' => 'admin.customers', 'icon' => 'fas fa-users', 'label' => 'Khách Hàng'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (isset($item['match']) && request()->routeIs($item['match']));
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="nav-link block py-3 px-4 rounded-xl transition font-semibold text-lg text-gray-200 
                          {{ $isActive ? 'nav-link-active' : 'bg-white/10 hover:bg-white/15' }}">
                    <i class="{{ $item['icon'] }} w-5 text-center mr-3"></i> {{ $item['label'] }}
                </a>
            @endforeach

        </nav>

        {{-- Logout --}}
        <div class="mt-auto pt-6 border-t border-white/10">
            <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                @csrf
                <button type="submit"
                        class="w-full py-3 px-6 rounded-xl bg-red-600 hover:bg-red-700 font-bold text-lg shadow-lg shadow-red-600/30 transition duration-300">
                    <i class="fas fa-sign-out-alt mr-3"></i> Đăng xuất
                </button>
            </form>
        </div>

    </aside>

    <main class="flex-1 overflow-y-auto bg-black/30 backdrop-blur-sm">
        <div class="p-8">
            
            {{-- HEADER NỘI DUNG --}}
            <div class="mb-8 border-b border-white/10 pb-4">
                <h2 class="text-3xl font-extrabold text-white">@yield('title')</h2>
                <p class="text-gray-400 text-sm mt-1">@yield('subtitle', 'Quản lý và điều hành hệ thống GhienCine.')</p> {{-- Sửa subtitle --}}
            </div>

            @yield('content')
        </div>
    </main>

</div>

@stack('scripts')
</body>
</html>