<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = 'combos';
    protected $primaryKey = 'combo_id';
    public $timestamps = false;

    protected $fillable = ['combo_name', 'description', 'price', 'image', 'status'];

    // Rất quan trọng: ép kiểu để trong code dùng $combo->status như boolean (true/false)
    protected $casts = [
        'status' => 'boolean' // 1 → true, 0 → false → cực tiện trong Blade
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_combos', 'combo_id', 'booking_code')
                    ->withPivot('quantity', 'combo_price');
    }

    // Scope để lấy combo đang hoạt động (dùng ở frontend)
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}