<header class="fixed top-0 left-0 right-0 z-[999999] transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-gray-100 shadow-sm supports-[backdrop-filter]:bg-white/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl overflow-hidden shadow-lg ring-2 ring-purple-500/30 group-hover:ring-purple-400/60 group-hover:shadow-2xl group-hover:shadow-purple-500/40 transition-all duration-500 transform group-hover:scale-110 group-hover:-rotate-3">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="GhienCine Logo" 
                             class="w-full h-full object-cover">
                    </div>
                    <span class="font-heading text-2xl font-extrabold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent group-hover:from-purple-600 group-hover:to-pink-600 transition-all duration-300">
                        GhienCine
                    </span>
                </a>
            </div>

           <!-- Menu desktop -->
            <nav class="hidden lg:flex items-center space-x-8">
                @php
                    $navLinks = [
                        [
                            'route' => 'home',
                            'label' => 'Trang chủ',
                            'url'   => route('home')
                        ],
                        [
                            'route' => 'movies.all',
                            'label' => 'Phim đang chiếu',
                            'query' => ['tab' => 'showing'],
                            'url'   => route('movies.all', ['tab' => 'showing'])
                        ],
                        [
                            'route' => 'movies.all',
                            'label' => 'Sắp khởi chiếu',
                            'query' => ['tab' => 'upcoming'],
                            'url'   => route('movies.all', ['tab' => 'upcoming'])
                        ],
                        [
                            'route' => 'movies.all',
                            'label' => 'Suất chiếu sớm',
                            'query' => ['tab' => 'special'],
                            'url'   => route('movies.all', ['tab' => 'special']),
                            'highlight' => true // để giữ hiệu ứng HOT và gạch vàng
                        ],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    @php
                        $isActive = request()->fullUrlIs($link['url'] . '*');
                    @endphp

                    <a href="{{ $link['url'] }}"
                    class="relative px-4 py-2 text-sm font-bold transition-all duration-300 group
                            {{ $isActive ? 'text-yellow-600' : 'text-gray-600 hover:text-purple-700' }}
                            {{ isset($link['highlight']) ? 'flex items-center gap-2' : '' }}">

                        {{ $link['label'] }}

                        {{-- Gạch chân gradient bình thường --}}
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-purple-600 to-pink-600 
                                    transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>

                        {{-- Gạch chân vàng + hiệu ứng khi active (Suất chiếu sớm) --}}
                        @if(isset($link['highlight']) && $isActive)
                            <span class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-yellow-400 via-orange-500 to-pink-500 
                                        rounded-full shadow-lg shadow-yellow-500/50 animate-pulse"></span>
                        @endif

                        {{-- Nhãn HOT cho Suất chiếu sớm --}}
                        @if(isset($link['highlight']))
                            <span class="absolute -top-2 -right-3 text-xs bg-yellow-500 text-black px-2 py-0.5 rounded-full font-bold animate-pulse shadow-md">
                                HOT
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <!-- Right side: Avatar + Auth -->
            <div class="flex items-center gap-4">

                @auth
                    {{-- GIỮ NGUYÊN 100% LOGIC CŨ CỦA BẠN --}}
                    @php
                        $userAvatar = Auth::user()->ava ?? null;
                        $avatarUrl = null;

                        if ($userAvatar) {
                            $avatarPath = 'storage/' . $userAvatar;
                            if (file_exists(public_path($avatarPath))) {
                                $avatarUrl = asset($avatarPath);
                            }
                        }

                        // Fallback UI Avatars nếu không có ảnh
                        if (!$avatarUrl) {
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name ?? Auth::user()->email) 
                                        . '&background=9333ea&color=fff&bold=true&rounded=true&size=128&format=svg';
                        }
                    @endphp

                    <div class="relative group">
                        <!-- Nút avatar -->
                        <button class="flex items-center gap-3 px-4 py-2 rounded-full hover:bg-gray-100 transition-all duration-300 border border-transparent hover:border-gray-200">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 p-[2.5px] shadow-lg ring-2 ring-transparent group-hover:ring-purple-400/60 transition-all">
                                <div class="w-full h-full rounded-full overflow-hidden bg-white">
                                    <img src="{{ $avatarUrl }}"
                                         alt="Avatar"
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? Auth::user()->email) }}&background=9333ea&color=fff&bold=true&rounded=true'">
                                </div>
                            </div>

                            <span class="hidden md:block text-sm font-bold text-gray-800 group-hover:text-purple-700 transition">
                                {{ Str::limit(Auth::user()->full_name ?? Auth::user()->email, 12) }}
                            </span>

                            <svg class="w-4 h-4 text-gray-500 group-hover:rotate-180 transition-transform duration-300"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100 z-50">
                            <!-- Header: Avatar lớn + tên + email -->
                            <div class="p-5 border-b border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 p-[3px]">
                                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full rounded-full object-cover bg-white">
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-lg">{{ Auth::user()->full_name ?? 'Người dùng' }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu items -->
                            <div class="py-3">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-4 px-5 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="font-medium">Hồ sơ cá nhân</span>
                                </a>

                                <a href="{{ route('profile.history') }}" class="flex items-center gap-4 px-5 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Vé đã đặt</span>
                                </a>

                                @if(!Auth::user()->provider)
                                    <a href="{{ route('password.change') }}" class="flex items-center gap-4 px-5 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11 8 13.536 4.5 10a5.5 5.5 0 00-5.5 5.5v2h2a2 2 0 002 2h2a2 2 0 002-2v-2.288l2-2 3.854-3.854A6 6 0 0015 7z"/>
                                        </svg>
                                        <span class="font-medium">Đổi mật khẩu</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-gray-200 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-4 px-5 py-3 text-red-600 hover:bg-red-50 transition font-medium rounded-b-2xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-gray-700 hover:text-purple-600 transition">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-7 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-full hover:shadow-lg hover:shadow-purple-500/50 transition-all duration-300 transform hover:-translate-y-0.5">
                            Đăng ký ngay
                        </a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <button type="button" class="lg:hidden p-2 text-gray-600 hover:text-purple-600 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>