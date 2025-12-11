<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'user_id', 'booking_code', 'amount',
        'payment_method', 'status', 'paid_at', 'created_at'
    ];

    protected $dates = ['paid_at', 'created_at'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'booking_code', 'booking_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}