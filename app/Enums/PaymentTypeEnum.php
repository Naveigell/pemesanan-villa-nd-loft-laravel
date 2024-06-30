<?php

namespace App\Enums;

use App\Enums\Interfaces\HasLabel;

enum PaymentTypeEnum: string implements HasLabel
{
    case CSTORE = 'cstore';
    case CREDIT_CARD = 'credit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case GOPAY = 'gopay';
    case QRIS = 'qris';
    case SHOPEE_PAY = 'shopee_pay';
    case ECHANNEL = 'echannel';
    case AKULAKU = 'akulaku';

    /**
     * Returns the label for this enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::CSTORE => 'Toko',
            self::CREDIT_CARD => 'Kartu Kredit',
            self::BANK_TRANSFER => 'Transfer Bank',
            self::GOPAY => 'Gopay',
            self::QRIS => 'QRIS',
            self::SHOPEE_PAY => 'Shopee Pay',
        };
    }

    /**
     * Check if the object is a qris.
     *
     * @return bool
     */
    public function isAkulaku()
    {
        return $this === self::AKULAKU;
    }

    /**
     * Check if the object is a store.
     *
     * @return bool
     */
    public function isEChannel()
    {
        return $this === self::ECHANNEL;
    }

    /**
     * Check if the object is a shopee pay.
     *
     * @return bool
     */
    public function isShopeePay()
    {
        return $this === self::SHOPEE_PAY;
    }

    /**
     * Check if the object is a credit card.
     *
     * @return bool
     */
    public function isCreditCard()
    {
        return $this === self::CREDIT_CARD;
    }

    /**
     * Check if the object is a bank transfer.
     *
     * @return bool
     */
    public function isBankTransfer()
    {
        return $this === self::BANK_TRANSFER;
    }

    /**
     * Check if the object is a gopay.
     *
     * @return bool
     */
    public function isGopay()
    {
        return $this === self::GOPAY;
    }

    /**
     * Check if the object is a qris.
     *
     * @return bool
     */
    public function isQris()
    {
        return $this === self::QRIS;
    }

    /**
     * Check if the object is a store.
     *
     * @return bool
     */
    public function isStore()
    {
        return $this === self::CSTORE;
    }
}
