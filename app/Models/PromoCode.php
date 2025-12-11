<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promocode extends Model
{
    protected $table = 'promocode';
    protected $primaryKey = 'promo_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'promo_code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status'
    ];

    protected $dates = ['start_date', 'end_date'];

    protected $casts = [
        'discount_type'   => 'integer',
        'discount_value'  => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'usage_limit'     => 'integer',
        'used_count'      => 'integer',
        'status'          => 'integer',
    ];

    // ✅ THÊM RELATIONSHIP
    public function userUsages()
    {
        return $this->hasMany(PromoUserUsage::class, 'promo_code', 'promo_code');
    }

    // Scope lấy mã còn hiệu lực
    public function scopeActive($query)
    {
        return $query->where('status', 1)
                     ->whereDate('start_date', '<=', Carbon::today())
                     ->whereDate('end_date', '>=', Carbon::today())
                     ->where(function ($q) {
                         $q->whereNull('usage_limit')
                           ->orWhereRaw('used_count < usage_limit');
                     });
    }

    // ✅ KIỂM TRA USER ĐÃ DÙNG MÃ NÀY CHƯA
    public function isUsedByUser($userId): bool
    {
        return $this->userUsages()
                    ->where('user_id', $userId)
                    ->exists();
    }

    // Kiểm tra mã có thể sử dụng ngay được không
    public function isUsable(): bool
    {
        return $this->status == 1 &&
               Carbon::now()->between($this->start_date, $this->end_date) &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    // Tăng lượt sử dụng
    public function incrementUsed(): void
    {
        $this->increment('used_count');
    }

    // Tính toán giá trị giảm giá dựa trên tổng tiền
    public function calculateDiscount(float $total): float
    {
        if ($this->discount_type == 1) { // phần trăm
            $discount = $total * ($this->discount_value / 100);
        } else { // tiền mặt (discount_type = 2)
            $discount = $this->discount_value;
        }

        return round($discount, 2);
    }
}