<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum BookingStatus: string implements HasLabel, HasHtmlBadge
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';

    /**
     * Checks if the current instance of the BookingStatus enum is approved.
     *
     * @return bool Returns true if the current instance is approved, false otherwise.
     */
    public function isApproved()
    {
        return $this === self::APPROVED;
    }

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

    public function toHtmlBadge()
    {
        return match ($this) {
            self::PENDING => '<span class="badge badge-warning">' . $this->label() . '</span>',
            self::APPROVED => '<span class="badge badge-success">' . $this->label() . '</span>',
            self::CANCELLED => '<span class="badge badge-danger">' . $this->label() . '</span>',
        };
    }
}
