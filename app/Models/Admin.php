<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'is_super', 'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super'          => 'boolean',
        'status'            => 'integer',
    ];

    /**
     * Hash password khi lưu
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Check xem admin có active không
     */
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    /**
     * Check xem admin có phải super admin không
     */
    public function isSuper(): bool
    {
        return $this->is_super === true;
    }
}