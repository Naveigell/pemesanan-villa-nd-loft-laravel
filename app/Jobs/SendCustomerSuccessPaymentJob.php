<?php

namespace App\Jobs;

use App\Enums\RoomPriceTypeEnum;
use App\Mail\CustomerSuccessPayment;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomerSuccessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Booking $booking;

    /**
     * The room price type.
     */
    private RoomPriceTypeEnum $type;

    /**
     * The total price.
     *
     * @var int
     */
    private $totalPrice;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, RoomPriceTypeEnum $type, $totalPrice)
    {
        $this->booking    = $booking;
        $this->totalPrice = $totalPrice;
        $this->type       = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->booking->customer_email)->send(new CustomerSuccessPayment($this->booking, $this->type, $this->totalPrice));
    }
}
