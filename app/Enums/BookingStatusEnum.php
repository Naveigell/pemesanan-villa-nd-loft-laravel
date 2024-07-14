<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum BookingStatusEnum: string implements HasLabel, HasHtmlBadge
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
     * Checks if the current instance of the BookingStatus enum is pending.
     *
     * @return bool Returns true if the current instance is pending, false otherwise.
     */
    public function isPending()
    {
        return $this === self::PENDING;
    }

    /**
     * Checks if the current instance of the BookingStatus enum is cancelled.
     *
     * @return bool Returns true if the current instance is cancelled, false otherwise.
     */
    public function isCancelled()
    {
        return $this === self::CANCELLED;
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
