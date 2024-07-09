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

    /**
     * Get the price as an integer.
     *
     * @return int The price as an integer.
     */
    public function getPriceIntegerAttribute()
    {
        return intval($this->price);
    }

    /**
     * Get the formatted room price as an integer.
     *
     * @return string The formatted room price as an integer.
     */
    public function getRoomPriceIntegerFormattedAttribute()
    {
        return format_price($this->getPriceIntegerAttribute());
    }

    /**
     * Multiply the price by the given value and format it.
     *
     * @param int $value The value to multiply the price by. Default is 1.
     * @return string The formatted price after multiplication.
     */
    public function priceFormattedMultiplyBy($value = 1)
    {
        return format_price($this->price * $value);
    }
}
