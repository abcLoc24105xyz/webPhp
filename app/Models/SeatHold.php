<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Show;
use App\Models\Seat;
use App\Models\User;

class SeatHold extends Model
{
    protected $table = 'seat_holds';
    protected $fillable = ['show_id', 'seat_id', 'user_id', 'expires_at', 'created_at'];
    public $timestamps = false;

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id', 'show_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
