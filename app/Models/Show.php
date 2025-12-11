<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Show extends Model
{
    protected $table = 'shows';
    protected $primaryKey = 'show_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'show_id', 'movie_id', 'cinema_id', 'room_code', 'show_date',
        'start_time', 'end_time', 'remaining_seats'
    ];

    // ĐÂY LÀ CHÌA KHÓA – BẮT BUỘC PHẢI CÓ
    protected $casts = [
        'show_date'      => 'date:Y-m-d',     // chuyển string → Carbon
        'start_time'     => 'string',
        'end_time'       => 'string',
        'remaining_seats'=> 'integer',
    ];

    // Nếu bạn muốn format mặc định khi lấy ra (tùy chọn)
    protected $appends = ['formatted_date', 'formatted_time'];

    public function getFormattedDateAttribute()
    {
        return $this->show_date?->format('d/m/Y') ?? '—';
    }

    public function getFormattedTimeAttribute()
    {
        return substr($this->start_time, 0, 5) . ' → ' . substr($this->end_time, 0, 5);
    }

    // Relations
    public function movie()   { return $this->belongsTo(Movie::class, 'movie_id'); }
    public function cinema()  { return $this->belongsTo(Cinema::class, 'cinema_id'); }
    public function room()    { return $this->belongsTo(Room::class, 'room_code', 'room_code'); }
    public function reservations() { return $this->hasMany(Reservation::class, 'show_id', 'show_id'); }
    public function seatHolds()    { return $this->hasMany(SeatHold::class, 'show_id', 'show_id'); }
}