{{-- resources/views/advertise.blade.php --}}
@extends('layouts.app')
@section('title', 'Liên hệ quảng cáo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-black text-white py-20 px-6">
    <div class="max-w-5xl mx-auto grid lg:grid-cols-2 gap-12 items-center">

        <!-- Phần trái: Thông tin -->
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-yellow-400 mb-8">
                HỢP TÁC QUẢNG CÁO
            </h1>
            <p class="text-xl text-gray-300 leading-relaxed mb-8">
                Với hơn <strong>2 triệu lượt đặt vé mỗi tháng</strong>, chúng tôi là nền tảng lý tưởng để thương hiệu của bạn tiếp cận đúng khách hàng mục tiêu: giới trẻ, gia đình yêu phim ảnh.
            </p>
            <div class="space-y-4 text-lg">
                <p>Đặt banner, trailer, standee tại rạp</p>
                <p>Quảng cáo trước phim (pre-roll)</p>
                <p>Sự kiện ra mắt phim, họp báo</p>
                <p>Combo đồng thương hiệu</p>
                <p>Email/SMS marketing đến khách hàng thành viên</p>
            </div>
        </div>

        <!-- Phần phải: Form liên hệ -->
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/20 rounded-3xl p-10 shadow-2xl">
            <h2 class="text-3xl font-bold text-yellow-400 mb-8 text-center">Gửi yêu cầu hợp tác</h2>
            <form action="https://formspree.io/f/your-id" method="POST" class="space-y-6">
                <input type="text" name="name" placeholder="Họ tên / Công ty" required
                       class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-yellow-400 transition">
                <input type="email" name="email" placeholder="Email" required
                       class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-yellow-400 transition">
                <input type="tel" name="phone" placeholder="Số điện thoại" required
                       class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-yellow-400 transition">
                <textarea name="message" rows="5" placeholder="Nội dung hợp tác mong muốn..." required
                          class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-yellow-400 transition"></textarea>
                <button type="submit"
                        class="w-full py-5 bg-gradient-to-r from-pink-500 to-yellow-500 text-black font-bold text-xl rounded-xl hover:scale-105 transition transform">
                    GỬI YÊU CẦU
                </button>
            </form>
            <p class="text-center text-gray-400 mt-6 text-sm">
                Hoặc email trực tiếp: <a href="mailto:advertise@ghiencine.vn" class="text-yellow-400 hover:underline">quangcao@cinema.vn</a>
            </p>
        </div>

    </div>
</div>
@endsection