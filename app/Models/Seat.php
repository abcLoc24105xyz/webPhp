<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';
    protected $primaryKey = 'seat_id';
    public $timestamps = false;

    protected $fillable = ['room_code', 'seat_num', 'seat_type', 'default_price'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_code', 'room_code');
    }

    // Ghế đang bị giữ tạm
    public function seatHolds()
    {
        return $this->hasMany(SeatHold::class, 'seat_id');
    }

    // Ghế đã được đặt
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_seats', 'seat_id', 'booking_code')
                    ->withPivot('seat_price');
    }
}