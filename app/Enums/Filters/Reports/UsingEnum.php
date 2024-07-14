<?php

namespace App\Enums\Filters\Reports;

use App\Enums\Interfaces\HasLabel;

enum UsingEnum: string implements HasLabel
{
    case PAID_DATE = 'paid_date';
    case BOOKING_DATE = 'booking_date';

    /**
     * Determines the label of the enum
     *
     * @return string
     */
    public function label()
    {
        return match ($this) {
            self::BOOKING_DATE => 'Tanggal Booking',
            self::PAID_DATE => 'Tanggal Dibayar',
        };
    }

    /**
     * Checks if the current instance is of the type `PAID_DATE`.
     *
     * @return bool Returns `true` if the current instance is of the type `PAID_DATE`, `false` otherwise.
     */
    public function isPaidDate()
    {
        return $this === self::PAID_DATE;
    }

    /**
     * Checks if the current instance is of the type `BOOKING_DATE`.
     *
     * @return bool Returns `true` if the current instance is of the type `BOOKING_DATE`, `false` otherwise.
     */
    public function isBookingDate()
    {
        return $this === self::BOOKING_DATE;
    }
}
