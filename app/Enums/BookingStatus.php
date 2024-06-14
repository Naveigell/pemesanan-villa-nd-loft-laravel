<?php

namespace App\Enums;

use App\Enums\Interfaces\HasLabel;

enum BookingStatus: string implements HasLabel
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';

    /**
     * Determines the label of the enum
     *
     * @return string
     */
    public function label()
    {
        switch ($this) {
            case self::PENDING:
                return 'Pending';
            case self::APPROVED:
                return 'Diterima';
            case self::CANCELLED:
                return 'Dibatalkan';
        }
    }
}
