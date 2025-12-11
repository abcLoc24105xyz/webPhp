<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoUserUsage extends Model
{
    protected $table = 'promo_user_usage';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'promo_code',
        'user_id',
        'booking_code'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function promo()
    {
        return $this->belongsTo(Promocode::class, 'promo_code', 'promo_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'booking_code', 'booking_code');
    }
}