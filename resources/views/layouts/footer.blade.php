<footer class="relative bg-slate-900 text-slate-300 mt-auto">
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-600 via-pink-500 to-purple-600"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center text-white">
                        <img src="{{ asset('images/logo.png') }}" 
                            alt="GhienCine Logo" 
                            class="w-full h-full object-cover">
                    </div>
                    <span class="text-2xl font-heading font-bold text-white tracking-tight">GhienCine</span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Trải nghiệm điện ảnh tuyệt vời nhất tại rạp chiếu phim gần bạn. Đặt vé nhanh chóng, thanh toán tiện lợi.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all duration-300 group">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all duration-300 group">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="text-white font-heading font-bold text-lg mb-6">Khám phá</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-purple-400 hover:pl-1 transition-all">Trang chủ</a></li>
                    <li><a href="{{ route('movies.all', ['tab' => 'showing']) }}" class="hover:text-purple-400 hover:pl-1 transition-all">Phim đang chiếu</a></li>
                    <li><a href="{{ route('movie.featured') }}" class="hover:text-purple-400 hover:pl-1 transition-all font-medium">Phim nổi bật</a></li>               
                    <li><a href="{{ route('promotions') }}" class="hover:text-purple-400 hover:pl-1 transition-all font-medium">Khám phá ưu đãi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-heading font-bold text-lg mb-6">Điều khoản</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('terms') }}" class="hover:text-purple-400 hover:pl-1 transition-all">Điều khoản chung</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-purple-400 hover:pl-1 transition-all">Chính sách bảo mật</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-purple-400 hover:pl-1 transition-all">Câu hỏi thường gặp</a></li>
                    <li><a href="{{ route('advertise') }}" class="hover:text-purple-400 hover:pl-1 transition-all">Liên hệ quảng cáo</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-heading font-bold text-lg mb-6">Hỗ trợ 24/7</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <div>
                            <span class="block text-slate-400 text-xs">Hotline</span>
                            <span class="text-white font-bold text-lg hover:text-purple-400 transition cursor-pointer">1900 1234</span>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="hover:text-white transition cursor-pointer">support@ghiencine.vn</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-500 text-center md:text-left">
                &copy; {{ date('Y') }} GhienCine Vietnam. All rights reserved.
            </p>
            <div class="flex gap-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Visa.svg/1200px-Visa.svg.png" alt="Visa" class="h-6 opacity-50 hover:opacity-100 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="Mastercard" class="h-6 opacity-50 hover:opacity-100 transition">
                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="Momo" class="h-6 opacity-50 hover:opacity-100 transition">
            </div>
        </div>
    </div>
</footer>