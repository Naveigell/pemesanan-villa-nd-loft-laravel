<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'price', 'color'];

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facility')->withTimestamps();
    }

    public function getPriceIntegerAttribute()
    {
        return intval($this->price);
    }
}
