<?php

namespace App\Traits;

use App\Enums\RoomPriceTypeEnum;
use Carbon\Carbon;

trait CanFormatDateTimeByType
{
    /**
     * Format date time by type
     *
     * @param RoomPriceTypeEnum $type
     * @param string $from
     * @param string $until
     * @return object{'from': Carbon, 'until': Carbon}
     */
    public function formatDateTime(RoomPriceTypeEnum $type, $from, $until)
    {
        return (object) [
            'from'  => $this->getCarbonFormatted($type, $from),
            'until' => $this->getCarbonFormatted($type, $until, false),
        ];
    }

    /**
     * Get carbon formatted by type and get start of or end of
     *
     * @param RoomPriceTypeEnum $enum
     * @param string $date
     * @param bool $isStartOfDate
     * @return Carbon
     */
    public function getCarbonFormatted(RoomPriceTypeEnum $enum, $date, $isStartOfDate = true)
    {
        // set all time to start example: 00:00:00 to prevent calculation bugs in the future
        // handle different types of different dates
        if ($enum->isYear()) {
            return Carbon::createFromFormat('Y', $date)->{$isStartOfDate ? 'startOfYear' : 'endOfYear'}();
        }

        if ($enum->isMonth()) {
            return Carbon::createFromFormat('Y-m', $date)->{$isStartOfDate ? 'startOfMonth' : 'endOfMonth'}();
        }

        if ($enum->isDay()) {
            return Carbon::createFromFormat('Y-m-d', $date)->{$isStartOfDate ? 'startOfDay' : 'endOfDay'}();
        }

        return Carbon::parse($date)->{$isStartOfDate ? 'startOfDay' : 'endOfDay'}();
    }

    /**
     * Get the diff of date by its type
     *
     * @param RoomPriceTypeEnum $enum
     * @param string $from
     * @param string $until
     * @return int
     */
    public function diffOfDate(RoomPriceTypeEnum $enum, $from, $until)
    {
        $from  = Carbon::parse($from);
        $until = Carbon::parse($until);

        // set diff default to -1, because it will add 1 in the end
        $diff = -1;

        if ($enum->isYear()) {
            $diff = $from->diffInYears($until);
        }

        if ($enum->isMonth()) {
            $diff = $from->diffInMonths($until);
        }

        if ($enum->isDay()) {
            $diff = $from->diffInDays($until);
        }

        // should add 1 in the end and make the diff to 0 if the enum is not year, month or day
        return $diff + 1;
    }
}
