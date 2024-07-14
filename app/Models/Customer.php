<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'address'];

    /**
     * Get the formatted phone number attribute.
     *
     * This function retrieves the phone number attribute from the current object
     * and formats it by replacing the '+62' prefix with '0'. The formatted phone
     * number is then returned.
     *
     * @return string The formatted phone number.
     */
    public function getPhoneFormattedAttribute()
    {
        $phone = $this->phone;
        $phone = str_replace('+62', '+62', $phone);
        $phone = chunk_split($phone, 3, ' ');

        return $phone;
    }
}
