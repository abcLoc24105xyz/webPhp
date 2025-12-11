<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $type; // 'register' hoặc 'reset'

    public function __construct($otp, $name = null, $type = 'register')
    {
        $this->otp  = $otp;
        $this->name = $name ?? 'Quý khách';
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'reset'
            ? 'Đặt lại mật khẩu tài khoản GhienCine'
            : 'Xác minh tài khoản GhienCine';

        return $this->subject('GhienCine | ' . $subject)
                    ->view('emails.otp');
    }
}