<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum PaymentStatusEnum: string implements HasLabel, HasHtmlBadge
{
    case PENDING = 'pending';
    case SETTLEMENT = 'settlement';
    case FAILED = 'failed';
    case CANCEL = 'cancel';
    case EXPIRED = 'expired';

    /**
     * Returns the label for this enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::SETTLEMENT => 'Berhasil',
            self::FAILED => 'Gagal',
            self::CANCEL => 'Dibatalkan',
            self::EXPIRED => 'Kadaluarsa',
        };
    }

    /**
     * Check if the current status is valid
     *
     * @return bool
     */
    public function isSettlement()
    {
        return in_array($this, [self::SETTLEMENT]);
    }

    /**
     * Check if the current state is not valid
     *
     * @return bool
     */
    public function isNotValid()
    {
        return in_array($this, [self::FAILED]);
    }

    /**
     * Convert the data to a html badge.
     */
    public function toHtmlBadge()
    {
        return match ($this) {
            self::PENDING => '<span class="badge badge-warning">' . $this->label() . '</span>',
            self::SETTLEMENT => '<span class="badge badge-success">' . $this->label() . '</span>',
            self::FAILED, self::CANCEL, self::EXPIRED => '<span class="badge badge-danger">' . $this->label() . '</span>',
        };
    }
}
