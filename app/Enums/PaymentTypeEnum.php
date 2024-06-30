<?php

namespace App\Enums;

use App\Enums\Interfaces\HasLabel;

enum PaymentTypeEnum: string implements HasLabel
{
    case CSTORE = 'store';
    case CREDIT_CARD = 'credit_card';
    case BANK_TRANSFER = 'bank_transfer';


    /**
     * Returns the label for this enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::CSTORE => 'Toko (Indomaret atau Alfamart)',
            self::CREDIT_CARD => 'Kartu Kredit',
            self::BANK_TRANSFER => 'Transfer Bank',
        };
    }
}
