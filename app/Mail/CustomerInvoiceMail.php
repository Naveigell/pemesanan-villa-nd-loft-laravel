<?php

namespace App\Mail;

use App\Enums\RoomPriceTypeEnum;
use App\Models\Booking;
use App\Models\Transaction;
use App\Traits\CanFormatDateTimeByType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerInvoiceMail extends Mailable
{
    use Queueable, SerializesModels, CanFormatDateTimeByType;

    /**
     * @var Booking
     */
    private Booking $booking;

    /**
     * @var RoomPriceTypeEnum
     */
    private RoomPriceTypeEnum $type;

    /**
     * @var int
     */
    private $totalPrice;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, RoomPriceTypeEnum $type, $totalPrice)
    {
        $this->booking    = $booking;
        $this->type       = $type;
        $this->totalPrice = $totalPrice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pemesanan Kamar',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        [$booking, $type, $totalPrice] = [$this->booking, $this->type, $this->totalPrice];

        $diff = $this->diffOfDate($type, $booking->from_date->startOfDay(), $booking->until_date->startOfDay());

        return new Content(
            'layouts.email.guest.invoice',
            with: compact('booking', 'type', 'totalPrice', 'diff')
        );
    }
}
