<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé điện tử - Đặt Vé Thành Công - GhienCine</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 600px; margin: 30px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        /* Màu Header GhienCine */
        .header { 
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); 
            color: white; 
            padding: 30px 20px; 
            text-align: center; 
        }
        /* Điều chỉnh kích thước tiêu đề chính */
        .header h2 { margin: 0; font-size: 24px; font-weight: bold; } 
        .header p.brand-name { font-size: 16px; font-weight: normal; margin-top: 0; }
        .content { padding: 30px 25px; }
        /* Màu Info Box GhienCine */
        .booking-info { 
            background: #fcf6ff; /* Màu nền tím nhạt */
            padding: 18px; 
            border-radius: 10px; 
            margin: 18px 0; 
            border-left: 5px solid #a855f7; /* Màu tím đậm */
        }
        .booking-info h3 { margin: 0 0 15px 0; color: #a855f7; font-size: 18px; }
        .qr-code { text-align: center; margin: 30px 0; }
        .qr-code img { max-width: 280px; border: 8px solid white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
        /* Màu Button GhienCine */
        .button { 
            display: inline-block; 
            background: #ec4899; /* Màu hồng */
            color: white; 
            padding: 14px 32px; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: bold; 
            margin-top: 20px; 
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4); 
        }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #888; }
        .highlight { color: #d97706; font-weight: bold; font-size: 20px; } /* Màu Mã đơn hàng */
        .money { color:#10b981; font-size:22px; font-weight:bold; } /* Màu tổng tiền xanh lá cây */

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container { margin: 0; border-radius: 0; }
            .content { padding: 20px 15px; }
            .qr-code img { max-width: 80%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">            
            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 5px;">Vé Đã Được Xác Nhận!</h2>
            <p class="brand-name">GhienCine</p>
        </div>

        <div class="content">
            <p style="font-size: 16px;">Xin chào <strong>{{ $user->name }}</strong>,</p>
            <p style="font-size: 16px;">Vé xem phim của bạn tại GhienCine đã được đặt thành công. Vui lòng xem thông tin chi tiết và mã QR bên dưới:</p>

            {{-- Mã QR CODE --}}
            <div class="qr-code">
                <h3 style="color:#a855f7; margin-bottom: 5px;">MÃ QR CHECK-IN</h3>
                <img src="{{ $qrCodeUrl }}" alt="QR Code Vé">
                <p style="font-size: 16px; margin-top: 10px;">
                    <strong>Mã đơn hàng:</strong> 
                    <span class="highlight">{{ $bookingCode }}</span>
                </p>
                <p style="margin: 5px 0 0 0; color:#555;">Xuất trình mã này tại quầy để nhận vé giấy.</p>
            </div>
            
            <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

            <div class="booking-info">
                <h3>Thông Tin Suất Chiếu</h3>
                <p><strong>Phim:</strong> {{ $reservation->show->movie->title }}</p>
                <p><strong>Rạp:</strong> <strong>{{ $reservation->show->cinema->cinema_name }}</strong></p>
                <p><strong>Phòng:</strong> {{ $reservation->show->room->room_name ?? 'Chưa xác định' }}</p>
                <p><strong>Ngày chiếu:</strong> {{ \Carbon\Carbon::parse($reservation->show->show_date)->translatedFormat('l, d/m/Y') }}</p>
                <p><strong>Giờ chiếu:</strong> <span style="color:#ec4899; font-weight:bold;">{{ substr($reservation->show->start_time, 0, 5) }}</span></p>
            </div>

            <div class="booking-info">
                <h3>Ghế Đã Chọn ({{ count($seats) }} Ghế)</h3>
                <p style="margin-top: 10px; font-size: 15px;">
                    @forelse($seats as $seat)
                        <span style="display: inline-block; background: #fff; padding: 5px 10px; border: 1px solid #ddd; border-radius: 5px; margin: 3px;">
                            <strong>{{ $seat }}</strong>
                        </span>
                    @empty
                        <span style="color:#999;">Không có ghế nào được chọn</span>
                    @endforelse
                </p>
            </div>

            @if(isset($combos) && $combos->count() > 0)
            <div class="booking-info">
                <h3>Combo Đã Chọn</h3>
                @foreach($combos as $combo)
                    <p style="margin: 5px 0;">
                        • <strong>{{ $combo->combo_name }}</strong> 
                        × {{ $combo->quantity }} 
                        = <strong style="color: #e67e22;">{{ number_format($combo->combo_price * $combo->quantity) }}đ</strong>
                    </p>
                @endforeach
            </div>
            @endif
            
            <div style="text-align: center; background: #f0fff4; padding: 20px; border-radius: 10px; border: 1px solid #d0f0d0; margin-top: 25px;">
                 <p style="margin-bottom: 5px; font-size: 16px;"><strong>Tổng số tiền đã thanh toán:</strong></p>
                 <p style="margin: 0;">
                    <span class="money">
                         {{ number_format($reservation->total_amount) }}đ
                    </span>
                    @if($isFree) 
                        <span style="color:#27ae60; font-weight:bold; font-size: 18px;">(Miễn phí 100%)</span> 
                    @endif
                 </p>
            </div>
            
            <div style="text-align:center;">
                <a href="{{ $detailLink }}" class="button">Xem Chi Tiết Vé Của Bạn</a>
            </div>

            <hr style="border: none; border-top: 1px dashed #ddd; margin: 30px 0;">

            <p style="color:#7f8c8d; font-size:14px; margin-bottom: 5px;">
                <strong>LƯU Ý QUAN TRỌNG:</strong>
            </p>
            <ul style="list-style-type: disc; padding-left: 20px; font-size: 14px; color: #555;">
                <li>Vui lòng đến quầy vé trước giờ chiếu <strong>ít nhất 15 phút</strong>.</li>
                <li>Mã QR hoặc Mã đơn hàng ({{ $bookingCode }}) chỉ có hiệu lực một lần để đổi lấy vé giấy.</li>
                <li>Email này là vé điện tử của bạn. Vui lòng lưu trữ để đối chiếu khi cần.</li>
            </ul>
        </div>

        <div class="footer">
            <p style="margin-bottom: 5px;"><strong>GhienCine</strong> © {{ date('Y') }} • Hệ thống đặt vé trực tuyến</p>
            <p style="margin: 0;">Email: support@ghiencine.vn | Hotline: 1900 1234</p>
            <p style="margin-top: 5px; font-size: 11px;">Email này được gửi tự động • Vui lòng không trả lời</p>
        </div>
    </div>
</body>
</html>