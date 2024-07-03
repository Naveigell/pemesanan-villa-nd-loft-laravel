<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'color'];

    /**
     * Retrieves the facilities associated with the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facility')->withTimestamps();
    }

    /**
     * Retrieves the main image URL for the room.
     *
     * This attribute function loads the missing 'image' relationship and checks if it exists. If the image does not exist,
     * it returns the default missing image URL from the configuration. Otherwise, it returns the image URL.
     *
     * @return string The URL of the main image for the room.
     */
    public function getMainImageUrlAttribute()
    {
        $this->loadMissing('image');

        if (!$this->image) {
            return config('default_values.missing_image');
        }

        return $this->image->image_url;
    }

    /**
     * Retrieves the image associated with the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(RoomImage::class, 'room_id');
    }

    public function prices()
    {
        return $this->hasMany(RoomPrice::class, 'room_id');
    }

    /**
     * Get the bookings associated with the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getPriceIntegerAttribute()
    {
        return intval($this->price);
    }

    public function getPriceFormattedAttribute()
    {
        return format_price($this->price);
    }

    public function priceFormattedMultiplyBy($value = 1)
    {
        return format_price($this->price * $value);
    }
}
