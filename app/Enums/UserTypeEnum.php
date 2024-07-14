<?php

namespace App\Enums;

use App\Enums\Interfaces\HasHtmlBadge;
use App\Enums\Interfaces\HasLabel;

enum UserTypeEnum: string implements HasLabel, HasHtmlBadge
{
    case CUSTOMER = 'customer';
    case ADMIN = 'admin';

    /**
     * Determines the label of the enum
     *
     * @return string
     */
    public function label()
    {
        return match ($this) {
            self::CUSTOMER => 'Customer',
            self::ADMIN => 'Admin',
        };
    }

    /**
     * Check if the current instance is a customer.
     *
     * @return bool Returns true if the current instance is a customer, false otherwise.
     */
    public function isCustomer()
    {
        return $this === self::CUSTOMER;
    }

    /**
     * Check if the current instance is an admin.
     *
     * @return bool Returns true if the current instance is an admin, false otherwise.
     */
    public function isAdmin()
    {
        return $this === self::ADMIN;
    }

    /**
     * Convert the data to a html badge.
     */
    public function toHtmlBadge()
    {
        return match ($this) {
            self::ADMIN => '<span class="badge badge-primary">' . strtolower($this->label()) . '</span>',
            self::CUSTOMER => '<span class="badge badge-warning">' . strtolower($this->label()) . '</span>',
        };
    }
}
