<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationSeat extends Model
{
    protected $table = 'reservation_seats';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['booking_code', 'seat_id'];

    protected $fillable = ['booking_code', 'seat_id', 'seat_price'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'booking_code', 'booking_code');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }
    
    public function reservationSeats()
    {
        return $this->hasMany(ReservationSeat::class, 'seat_id', 'seat_id');
    }
}