<?php

namespace App\Models;

use App\Enums\RoomPriceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'price', 'type'];

    protected $casts = [
        'type' => RoomPriceTypeEnum::class,
    ];

    /**
     * Get the formatted room price.
     *
     * @return string The formatted room price.
     */
    public function getRoomPriceFormattedAttribute()
    {
        return format_price($this->price);
    }
}
