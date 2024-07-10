<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
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
                    if ($status->isSettlement()) {
                        $booking->update(['status' => BookingStatus::APPROVED->value]);
                    } else {
                        $booking->update(['status' => BookingStatus::CANCELLED->value]);
                    }

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
