<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movie extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'movie_id';
    public $timestamps = false;

    // Status constants
    const STATUS_COMING_SOON = 1;
    const STATUS_SHOWING = 2;
    const STATUS_ENDED = 3;

    // Age limit constants
    const AGE_LIMIT_P = 0;
    const AGE_LIMIT_T13 = 13;
    const AGE_LIMIT_T16 = 16;
    const AGE_LIMIT_T18 = 18;

    protected $fillable = [
        'title', 'slug', 'cate_id', 'director', 'duration', 'description',
        'release_date', 'early_premiere_date', 'poster', 'trailer', 
        'rating', 'age_limit', 'status', 'created_at'
    ];

    protected $casts = [
        'release_date'         => 'date',
        'early_premiere_date'  => 'date',
        'created_at'           => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id');
    }

    public function shows()
    {
        return $this->hasMany(Show::class, 'movie_id');
    }

    /**
     * Scope: Lấy phim sắp chiếu
     */
    public function scopeComingSoon($query)
    {
        return $query->where('status', self::STATUS_COMING_SOON);
    }

    /**
     * Scope: Lấy phim đang chiếu
     */
    public function scopeShowing($query)
    {
        return $query->where('status', self::STATUS_SHOWING);
    }

    /**
     * Scope: Lấy phim đã kết thúc
     */
    public function scopeEnded($query)
    {
        return $query->where('status', self::STATUS_ENDED);
    }

    /**
     * Kiểm tra có chiếu sớm không
     */
    public function hasEarlyPremiere()
    {
        return !is_null($this->early_premiere_date);
    }

    /**
     * Lấy ngày bắt đầu chiếu
     */
    public function getStartShowingDate()
    {
        return $this->hasEarlyPremiere() 
            ? $this->early_premiere_date 
            : $this->release_date;
    }

    /**
     * Lấy nhãn status
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            self::STATUS_COMING_SOON => 'Sắp chiếu',
            self::STATUS_SHOWING     => 'Đang chiếu',
            self::STATUS_ENDED       => 'Kết thúc',
            default                  => 'Không xác định'
        };
    }

    /**
     * Lấy nhãn tuổi
     */
    public function getAgeLimitLabel()
    {
        return match($this->age_limit) {
            self::AGE_LIMIT_P   => 'P - Phổ biến',
            self::AGE_LIMIT_T13 => 'T13',
            self::AGE_LIMIT_T16 => 'T16',
            self::AGE_LIMIT_T18 => 'T18',
            default             => 'Không xác định'
        };
    }

    /**
     * Format release date
     */
    public function getFormattedReleaseDateAttribute()
    {
        return $this->release_date 
            ? $this->release_date->format('d/m/Y') 
            : 'Chưa xác định';
    }

    /**
     * Format early premiere date
     */
    public function getFormattedEarlyPremiereDateAttribute()
    {
        return $this->hasEarlyPremiere() 
            ? $this->early_premiere_date->format('d/m/Y') 
            : '-';
    }
}