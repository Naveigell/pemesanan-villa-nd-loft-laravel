<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Jobs\SendCustomerSuccessPaymentJob;
use App\Models\Booking;
use App\Utils\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->input('signature_key');

        // check signature if the notification is really from midtrans and send to our server
        if ($signature == Midtrans::test()->constructSignatureKey($request->input('order_id'), $request->input('status_code'), $request->input('gross_amount'))) {
            $transactionStatus = $request->input('transaction_status');
            $orderId           = $request->input('order_id');

            // get booking by it's order id that we got from midtrans
            $booking = Booking::with('latestPayment')->where('code', $orderId)->first(); // latest payment never be null

            if ($booking) {
                DB::beginTransaction();

                try {
                    $status = PaymentStatusEnum::tryFrom($transactionStatus);

                    // update booking status
                    $booking->update(['status' => $this->getBookingStatus($status->value)]);

                    // update payment
                    $this->updatePayment($request, $booking);

                    DB::commit();

                    // only send notification to customer if transaction status is settlement
                    if ($status->isSettlement()) {
                        // send notification to customer.
                        // gross amount never be null because we update the response above in updatePayment() method
                        dispatch(new SendCustomerSuccessPaymentJob($booking, $booking->latestPayment->room_type_price, $booking->latestPayment->gross_amount));
                    }
                } catch (\Exception $exception) {
                    Log::alert('Failed to update booking status: ' . $exception->getMessage());
                    DB::rollBack();
                }
            }
        }
    }

    /**
     * Retrieves the corresponding booking status based on the payment status.
     *
     * @param string $status The payment status.
     * @return string The corresponding booking status.
     */
    private function getBookingStatus($status)
    {
        // match booking status with payment status, if booking approved, set payment status to settlement, etc.
        $statuses = [
            PaymentStatusEnum::SETTLEMENT->value => BookingStatusEnum::APPROVED->value,
            PaymentStatusEnum::PENDING->value    => BookingStatusEnum::PENDING->value,
            PaymentStatusEnum::CANCEL->value     => BookingStatusEnum::CANCELLED->value,
        ];

        if (array_key_exists($status, $statuses)) {
            return $statuses[$status];
        }

        return BookingStatusEnum::PENDING->value;
    }

    /**
     * Updates the payment information of a booking.
     *
     * @param Request $request The HTTP request object.
     * @param Booking $booking The booking object.
     * @return void
     */
    private function updatePayment(Request $request, Booking $booking)
    {
        $booking->latestPayment->update([
            'response'           => json_encode($request->all()),
            'signature'          => $request->input('signature_key'),
            'status_code'        => $request->input('status_code'),
            'payment_type'       => $request->input('payment_type'),
            'transaction_status' => $request->input('transaction_status'),
            'paid_at'            => now()->toDateTimeString(),
        ]);
    }
}
