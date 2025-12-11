<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'reset' ? 'Đặt lại mật khẩu' : 'Xác minh tài khoản' }} - GhienCine</title>
    <style>
        /* Thiết lập chung */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        body { margin: 0; padding: 0; }

        /* Kiểu chữ và màu sắc thương hiệu */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        .header { 
            text-align: center; 
            padding: 30px 20px; 
            background: linear-gradient(135deg, #a855f7, #ec4899); /* Tím - Hồng */
            color: white; 
        }
        .header h2 { font-size: 28px; margin: 0; font-weight: bold; }
        .content { padding: 30px; text-align: center; color: #333333; }
        
        /* Mã OTP nổi bật */
        .otp-box {
            display: inline-block;
            background-color: #fef3c7; /* Vàng nhạt */
            border: 2px solid #fcd34d; /* Vàng đậm */
            border-radius: 8px;
            padding: 15px 30px;
            margin: 25px 0;
        }
        .otp { 
            font-size: 36px; 
            font-weight: 900; 
            color: #d97706; /* Vàng mạnh */
            letter-spacing: 5px; 
            margin: 0;
            display: block;
        }
        
        /* Chân trang */
        .footer { text-align: center; padding: 15px; font-size: 11px; color: #888; background-color: #f9f9f9; border-top: 1px solid #eeeeee; }
        a { color: #ec4899; text-decoration: none; font-weight: bold; }
        strong { color: #d97706; }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; margin: 0; border-radius: 0; }
            .content { padding: 20px; }
            .otp { font-size: 30px !important; }
            .header { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h2>GhienCine</h2>
        </div>
        
        {{-- Content --}}
        <div class="content">
            <h3 style="font-size: 20px; color: #444; margin-top: 0; margin-bottom: 20px;">
                Xin chào {{ $name }},
            </h3>
            <p style="font-size: 16px; line-height: 1.6;">
                {{ $type === 'reset' 
                    ? 'Bạn vừa yêu cầu đặt lại mật khẩu. Dưới đây là mã xác minh (OTP) của bạn:' 
                    : 'Cảm ơn bạn đã đăng ký tài khoản! Vui lòng sử dụng mã xác minh (OTP) dưới đây để kích hoạt tài khoản:' }}
            </p>
            
            <div class="otp-box">
                <span class="otp">{{ $otp }}</span>
            </div>
            
            <p style="font-size: 15px; line-height: 1.5; color: #555;">
                Vui lòng nhập mã này trong vòng <strong>5 phút</strong> để hoàn tất.
            </p>
            <p style="font-size: 14px; line-height: 1.5; color: #777; margin-top: 30px;">
                Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email.
            </p>
            <p style="font-size: 14px; color: #777;">
                Cần hỗ trợ? Liên hệ: <a href="mailto:support@ghiencine.vn" style="color: #a855f7;">support@ghiencine.vn</a>
            </p>
        </div>
        
        {{-- Footer --}}
        <div class="footer">
            <p style="margin: 0;">&copy; 2025 GhienCine. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>