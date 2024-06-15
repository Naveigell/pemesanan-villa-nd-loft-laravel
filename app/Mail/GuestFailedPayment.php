<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestFailedPayment extends Mailable
{
    use Queueable, SerializesModels;

    private Booking $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Gagal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content('layouts.email.guest.payment_failed',
            with: [
                'booking' => $this->booking
            ]
        );
    }
}
