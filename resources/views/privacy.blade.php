{{-- resources/views/privacy.blade.php --}}
@extends('layouts.app')

@section('title', 'Chính sách bảo mật')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-black text-white py-20 px-6">
    <div class="max-w-4xl mx-auto">

        <!-- Tiêu đề -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-500 drop-shadow-2xl mb-6">
                CHÍNH SÁCH BẢO MẬT
            </h1>
            <p class="text-lg text-gray-400">Cập nhật lần cuối: 11/12/2025</p>
        </div>

        <!-- Nội dung chính sách -->
        <div class="space-y-12 text-lg leading-relaxed text-gray-200">

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">1. Thông tin chúng tôi thu thập</h2>
                <p>Khi bạn sử dụng dịch vụ, chúng tôi có thể thu thập:</p>
                <ul class="list-disc pl-8 mt-3 space-y-2">
                    <li>Họ tên, số điện thoại, email</li>
                    <li>Thông tin thanh toán (không lưu số thẻ)</li>
                    <li>Lịch sử đặt vé, suất chiếu đã xem</li>
                    <li>Địa chỉ IP, loại thiết bị, trình duyệt</li>
                    <li>Cookie và dữ liệu hành vi trên website</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">2. Mục đích sử dụng thông tin</h2>
                <ul class="list-disc pl-8 space-y-2">
                    <li>Xử lý đặt vé và gửi mã vé qua SMS/Email</li>
                    <li>Gửi thông báo ưu đãi, phim mới (nếu bạn đồng ý)</li>
                    <li>Cải thiện trải nghiệm người dùng</li>
                    <li>Phòng chống gian lận và bảo mật hệ thống</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">3. Chúng tôi KHÔNG làm gì với dữ liệu của bạn</h2>
                <ul class="list-disc pl-8 space-y-2">
                    <li>Không bán, cho thuê thông tin cá nhân cho bên thứ ba</li>
                    <li>Không gửi spam hoặc quảng cáo không mong muốn</li>
                    <li>Không lưu trữ số thẻ tín dụng (được xử lý qua cổng thanh toán an toàn)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">4. Cookie & Công nghệ theo dõi</h2>
                <p>Chúng tôi sử dụng cookie để:</p>
                <ul class="list-disc pl-8 mt-2 space-y-1">
                    <li>Giữ trạng thái đăng nhập</li>
                    <li>Lưu ghế đang chọn trong 10 phút</li>
                    <li>Phân tích lưu lượng truy cập (Google Analytics)</li>
                </ul>
                <p class="mt-3">Bạn có thể tắt cookie trong trình duyệt, nhưng một số tính năng có thể không hoạt động.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">5. Quyền của bạn</h2>
                <p>Bạn có thể bất kỳ lúc nào:</p>
                <ul class="list-disc pl-8 mt-2 space-y-1">
                    <li>Yêu cầu xem, sửa, xóa thông tin cá nhân</li>
                    <li>Hủy nhận email/SMS khuyến mãi</li>
                    <li>Xóa tài khoản vĩnh viễn</li>
                </ul>
                <p class="mt-3">Liên hệ: <a href="mailto:support@cinema.vn" class="text-yellow-400 hover:underline">support@ghiencine.vn</a></p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-pink-400 mb-4">6. Bảo mật</h2>
                <p>Chúng tôi sử dụng mã hóa SSL, tường lửa và các biện pháp bảo mật tiêu chuẩn ngành để bảo vệ dữ liệu của bạn.</p>
            </section>

        </div>

        <!-- Nút quay lại -->
        <div class="text-center mt-20">
            <a href="{{ url()->previous() }}" 
               class="inline-block px-10 py-4 bg-white/10 backdrop-blur border border-white/20 rounded-full hover:bg-white/20 transition-all duration-300">
                ← Quay lại
            </a>
        </div>

    </div>
</div>
@endsection