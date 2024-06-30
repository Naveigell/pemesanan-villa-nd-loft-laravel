<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatusEnum;
use App\Traits\Booking\CanConstructUrlToShowDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Booking extends Model
{
    use HasFactory, CanConstructUrlToShowDetail;

    protected $fillable = [
        'room_id',
        'user_id',
        'code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'from_date',
        'until_date',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'from_date' => 'date',
        'until_date' => 'date',
    ];

    public function generateBookingCode()
    {
        $this->attributes['code'] = Uuid::uuid4()->toString();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if this transaction not belongs to any user
     *
     * @return bool
     */
    public function isNotBelongsToAnyCustomer()
    {
        return !$this->user_id;
    }

    /**
     * Checks if the current instance belongs to a specific user.
     *
     * @param User $user The user to compare against.
     * @return bool Returns true if the current instance belongs to the user, false otherwise.
     */
    public function isBelongsToUser(User $user)
    {
        // Compare the user IDs to determine if the current instance belongs to the user
        return $this->user_id == $user->id;
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Retrieves the latest paid payment for the current instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The query builder for the latest paid payment.
     */
    public function latestPaidPayment()
    {
        return $this->latestPayment()
                    ->where('transaction_status', PaymentStatusEnum::SETTLEMENT->value);
    }

    /**
     * Retrieves the latest payment associated with the current instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The latest payment associated with the current instance.
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)
                    ->latest();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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
     * Scope a query to exclude records where dates are not between the specified range.
     *
     * @param Builder $query The query builder instance
     * @param mixed $dates The dates to check against
     * @return void
     */
    public function scopeWhereNotBetweenDates(Builder $query, $dates)
    {
        $whereBookedAt = $this->whereMultipleBookedAt($query, $dates, ['id']);

        // and then we just use ->whereNotIn('id', $whereBookedAt) that we got above
        $query->whereNotIn('id', $whereBookedAt);
    }

    /**
     * Scope a query to include multiple booked dates.
     *
     * @param Builder $query The query builder instance
     * @param mixed $dates The dates to check against
     * @return Builder
     */
    public function scopeWhereMultipleBookedAt(Builder $query, $dates, $columns = ['*'])
    {
        $dates = is_array($dates) ? $dates : [$dates];

        // we loop the columns of dates and whe add every date into ->whereBookedAt(), so it will has a lot of ->whereBookedAt()
        // and then we get the id of all of them
        return collect($dates)->reduce(function (Builder $query, $date, $index) use ($columns) {
            // if this is first element in array, add where
            if ($index == 0) {
                return $query->whereBookedAt($date);
            }

            // another add or where
            return $query->orWhere(function (Builder $query) use ($date) {
                $query->whereBookedAt($date);
            });
        }, Booking::select($columns));
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
