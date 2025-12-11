<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['room_code', 'cinema_id', 'room_name', 'room_type', 'total_seats'];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class, 'cinema_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'room_code', 'room_code');
    }

    public function shows()
    {
        return $this->hasMany(Show::class, 'room_code', 'room_code');
    }
}