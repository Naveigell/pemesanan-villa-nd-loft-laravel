<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum PaymentStatusEnum: string implements HasLabel, HasHtmlBadge
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    /**
     * Returns the label for this enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::SUCCESS => 'Berhasil',
            self::FAILED => 'Gagal',
        };
    }

    /**
     * Check if the current status is valid
     *
     * @return bool
     */
    public function isValid()
    {
        return in_array($this, [self::SUCCESS]);
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
            self::PENDING => '<span class="badge badge-warning">' . $this->toLabel() . '</span>',
            self::SUCCESS => '<span class="badge badge-success">' . $this->toLabel() . '</span>',
            self::FAILED => '<span class="badge badge-danger">' . $this->toLabel() . '</span>',
        };
    }
}
