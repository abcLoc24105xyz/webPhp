<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationCombo extends Model
{
    protected $table = 'reservation_combos';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['booking_code', 'combo_id'];

    protected $fillable = ['booking_code', 'combo_id', 'quantity', 'combo_price'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'booking_code', 'booking_code');
    }

    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id');
    }
}