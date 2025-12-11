<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;  // ← BẮT BUỘC PHẢI CÓ DÒNG NÀY!
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // ← PHẢI CÓ HasFactory!!!

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'status',
        'otp_code',
        'otp_expiry',
        'password_changed_at',
        'ava',
        'provider', 'provider_id', 'provider_avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'provider_id',
    ];

    protected $casts = [
        'otp_expiry'          => 'datetime',
        'email_verified_at'   => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    // Quan hệ
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class, 'user_id');
    }

    public function seatHolds()
    {
        return $this->hasMany(\App\Models\SeatHold::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'user_id');
    }
}