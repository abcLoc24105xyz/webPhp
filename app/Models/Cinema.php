<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    protected $table = 'cinemas';
    protected $primaryKey = 'cinema_id';
    public $timestamps = false;

    protected $fillable = ['cinema_name', 'address', 'phone', 'status'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'cinema_id');
    }

    public function shows()
    {
        return $this->hasMany(Show::class, 'cinema_id');
    }
}