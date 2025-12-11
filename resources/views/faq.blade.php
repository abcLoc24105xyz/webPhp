{{-- resources/views/faq.blade.php --}}
@extends('layouts.app')
@section('title', 'Câu hỏi thường gặp')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-black text-white py-20 px-6">
    <div class="max-w-4xl mx-auto">

        <h1 class="text-5xl md:text-7xl font-black text-center text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500 drop-shadow-2xl mb-16">
            FAQ - CÂU HỎI THƯỜNG GẶP
        </h1>

        <div class="space-y-6">
            @php
                $faqs = [
                    ['q' => 'Làm sao để hủy hoặc đổi vé?', 'a' => 'Bạn không thể đổi suất chiếu khi đã thanh toán thành công.'],
                    ['q' => 'Tôi có được mang đồ ăn ngoài vào rạp không?', 'a' => 'Không được phép mang đồ ăn, thức uống từ bên ngoài vào rạp để đảm bảo vệ sinh và trải nghiệm chung.'],
                    ['q' => 'Làm sao để nhận mã vé?', 'a' => 'Sau khi thanh toán thành công, mã QR vé sẽ được gửi qua email đăng ký. Bạn cũng có thể xem trong mục "Lịch sử đặt vé".'],
                    ['q' => 'Trẻ em dưới mấy tuổi được miễn phí?', 'a' => 'Trẻ em dưới 1m được miễn phí vé nhưng phải ngồi cùng ghế với người lớn. Từ 1m trở lên tính vé bình thường.'],
                    ['q' => 'Có thể đặt ghế đôi (couple) online không?', 'a' => 'Có! Ghế couple được đánh dấu màu hồng và có thể chọn bình thường khi đặt vé.'],
                    ['q' => 'Tôi quên mật khẩu phải làm sao?', 'a' => 'Vào trang đăng nhập → chọn "Quên mật khẩu" → nhập email → hệ thống sẽ gửi OTP để đặt lại mật khẩu.'],
                    ['q' => 'Có hỗ trợ thanh toán qua online không?', 'a' => 'Có hỗ trợ thanh toán qua Momo, thẻ ATM nội địa, thẻ tín dụng Visa/MasterCard.'],
                    ['q' => 'Làm sao để trở thành hội viên VIP?', 'a' => 'Hiện tại hệ thống chưa có chương trình tích điểm khi đặt vé. Chúng tôi sẽ sớm cập nhật trong thời gian tới!'],
                ];
            @endphp

            @foreach($faqs as $item)
                <details class="group bg-white/5 backdrop-blur border border-white/10 rounded-2xl overflow-hidden shadow-lg hover:shadow-purple-500/20 transition-all duration-300">
                    <summary class="px-8 py-6 text-xl font-bold cursor-pointer flex justify-between items-center hover:bg-white/10 transition">
                        <span>{{ $item['q'] }}</span>
                        <svg class="w-6 h-6 transform group-open:rotate-180 transition-transform duration-300" fill-current text-yellow-400" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-8 pb-6 pt-2 text-gray-300 leading-relaxed text-lg">
                        {{ $item['a'] }}
                    </div>
                </details>
            @endforeach
        </div>

        <!-- Hỗ trợ thêm -->
        <div class="text-center mt-16">
            <p class="text-gray-400 text-lg mb-6">Vẫn chưa tìm thấy câu trả lời bạn cần?</p>
            <a href="mailto:support@ghiencine.vn" class="inline-block px-12 py-5 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-xl rounded-full hover:scale-105 transition-all shadow-xl">
                Liên hệ hỗ trợ 24/7
            </a>
        </div>

    </div>
</div>
@endsection