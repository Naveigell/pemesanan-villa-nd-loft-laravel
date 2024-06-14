<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'user_id', 'code', 'customer_name', 'customer_email', 'customer_phone', 'customer_address', 'from_date', 'until_date', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope a query to only include where the booking is inside 2 dates
     *
     * For examples:
     * $date is '2024-06-27'
     *
     * Booking data from_date is '2024-06-25' and until_date is '2024-06-30',
     * So this query is for check if $date is in range of '2024-06-25' and '2024-06-30'
     *
     * @param Builder $query
     * @param string $date
     * @return void
     */
    public function scopeWhereBookedAt(Builder $query, string $date)
    {
        // get where the booking is inside 2 dates
        $query->whereDate('from_date', '<=', $date)
              ->whereDate('until_date', '>=', $date);
    }

    /**
     * Scope a query to only include where the booking is inside 2 dates
     *
     * For examples:
     * if the booking data of from_date is '2024-06-27' and until_date is '2024-07-01',
     * we need to check if from_date is in range of the given $fromDate and $untilDate
     *
     * if the given $fromDate is '2024-06-25' and $untilDate is '2024-06-30', so we need to check if from_date is
     * inside '2024-06-25' and '2024-06-30'
     *
     * @param Builder $query
     * @param string $fromDate
     * @param string $untilDate
     * @return void
     */
    public function scopeWhereDateInsideRange(Builder $query, string $fromDate, string $untilDate)
    {
        $query->whereBetween('from_date', [$fromDate, $untilDate])
              ->orWhereBetween('until_date', [$fromDate, $untilDate]);
    }
}
