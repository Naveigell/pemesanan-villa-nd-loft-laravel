<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum RoomPriceTypeEnum: string implements HasLabel, HasHtmlBadge
{
    case YEAR = 'year';
    case MONTH = 'month';
    case DAY = 'day';

    /**
     * Convert the data to a html badge.
     */
    public function toHtmlBadge()
    {
        return match ($this) {
            self::YEAR,
            self::MONTH,
            self::DAY => '<span class="badge badge-success">' . $this->label() . '</span>',
        };
    }

    /**
     * Determines the label of the enum
     *
     * @return string
     */
    public function label()
    {
        return match ($this) {
            self::YEAR => 'Tahun',
            self::MONTH => 'Bulan',
            self::DAY => 'Hari',
        };
    }

    /**
     * Check if the current instance is equal to the YEAR constant.
     *
     * @return bool Returns true if the current instance is equal to the YEAR constant, false otherwise.
     */
    public function isYear()
    {
        return $this === self::YEAR;
    }

    /**
     * Check if the current instance is equal to the MONTH constant.
     *
     * @return bool Returns true if the current instance is equal to the MONTH constant, false otherwise.
     */
    public function isMonth()
    {
        return $this === self::MONTH;
    }

    /**
     * Check if the current instance is equal to the DAY constant.
     *
     * @return bool Returns true if the current instance is equal to the DAY constant, false otherwise.
     */
    public function isDay()
    {
        return $this === self::DAY;
    }
}
