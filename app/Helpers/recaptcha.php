<?php
// includes/recaptcha.php – Kiểm tra reCAPTCHA v3
function verifyRecaptcha($token) {
    $secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? 'your_secret_key_here';

    if (app()->environment('local')) {
         return true; // ← luôn cho qua khi chạy localhost
    }
        
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query([
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ])
        ]
    ]));

    $response = json_decode($response, true);
    
    // Score < 0.5 = bot → trả về false
    return $response['success'] && ($response['score'] ?? 1.0) >= 0.5;
}
?>