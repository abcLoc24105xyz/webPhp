<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'booking_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'booking_code',
        'user_id',
        'show_id',
        'total_amount',
        'status',
        'created_at'
    ];

    protected $dates = ['created_at'];

    // Quan hệ
    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // GHẾ ĐÃ ĐẶT – DÙNG belongsToMany VỚI COMPOSITE KEY QUA BẢNG TRUNG GIAN
    public function seats()
    {
        return $this->belongsToMany(
            Seat::class,
            'reservation_seats',
            'booking_code',   // khóa ngoại trong bảng trung gian
            'seat_id'         // khóa ngoại trong bảng trung gian
        )->withPivot('seat_price');
    }

    // COMBO ĐÃ CHỌN
    public function combos()
    {
        return $this->belongsToMany(
            Combo::class,
            'reservation_combos',
            'booking_code',
            'combo_id'
        )->withPivot('quantity', 'combo_price');
    }

    // THANH TOÁN
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_code', 'booking_code');
    }

    // Trạng thái
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}