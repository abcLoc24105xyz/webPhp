{{-- resources/views/terms.blade.php --}}
@extends('layouts.app')

@section('title', 'Điều khoản chung')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-black text-white py-20 px-6">
    <div class="max-w-4xl mx-auto">

        <!-- Tiêu đề trang -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-purple-500 drop-shadow-2xl mb-6">
                ĐIỀU KHOẢN CHUNG
            </h1>
            <p class="text-lg text-gray-400">Cập nhật lần cuối: 11/12/2025</p>
        </div>

        <!-- Nội dung điều khoản -->
        <div class="space-y-12 text-lg leading-relaxed text-gray-200">
            <section>
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">1. Chấp nhận điều khoản</h2>
                <p>Khi sử dụng website và dịch vụ đặt vé của chúng tôi, bạn đồng ý tuân thủ và bị ràng buộc bởi các điều khoản và điều kiện dưới đây.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">2. Đặt vé và thanh toán</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Vé đã mua không hoàn tiền, chỉ đổi suất trong vòng 2 giờ trước giờ chiếu (nếu còn ghế).</li>
                    <li>Thời gian giữ ghế: tối đa 10 phút kể từ khi chọn ghế.</li>
                    <li>Giá vé và combo có thể thay đổi theo từng rạp và thời điểm.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">3. Quy định vào rạp</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Tuổi xem phim tuân thủ theo phân loại độ tuổi của Cục Điện ảnh.</li>
                    <li>Không mang đồ ăn, thức uống từ bên ngoài vào rạp.</li>
                    <li>Tắt chuông điện thoại và giữ trật tự trong suốt buổi chiếu.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">4. Bảo mật thông tin</h2>
                <p>Chúng tôi cam kết bảo vệ thông tin cá nhân của khách hàng theo Chính sách bảo mật được công bố riêng.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">5. Thay đổi điều khoản</h2>
                <p>Công ty có quyền thay đổi điều khoản này bất kỳ lúc nào và sẽ thông báo trên website.</p>
            </section>
        </div>

        <!-- Nút quay lại -->
        <div class="text-center mt-20">
            <a href="{{ url()->previous() }}" 
               class="inline-block px-10 py-4 bg-white/10 backdrop-blur border border-white/20 rounded-full hover:bg-white/20 transition-all duration-300">
                Quay lại
            </a>
        </div>

    </div>
</div>
@endsection