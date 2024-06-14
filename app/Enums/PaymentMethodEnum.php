<?php

namespace App\Enums;

use App\Enums\Interfaces\HasLabel;

enum PaymentMethodEnum: string implements HasLabel
{
    case CASH = 'CASH';
    case CREDIT_CARD = 'CREDIT_CARD';
    case BANK_TRANSFER = 'BANK_TRANSFER';


    /**
     * Returns the label for this enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Kas',
            self::CREDIT_CARD => 'Kartu Kredit',
            self::BANK_TRANSFER => 'Transfer Bank',
        };
    }
}
