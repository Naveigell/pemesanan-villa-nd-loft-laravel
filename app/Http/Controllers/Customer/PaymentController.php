<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Traits\CanFormatDateTimeByType;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use CanFormatDateTimeByType;

    public function show(Request $request, Booking $booking)
    {
        $token     = $request->query('token');
        $code      = $request->query('code');
        $timestamp = $request->query('timestamp');

        // should have this query params
        abort_if(!$token || !$code || !$timestamp, 404);

        // only accept if booking is approved
        abort_if(!$booking->status->isApproved(), 404);

        // should be valid token
        abort_if(!$booking->validateToken($token), 404);

        // should be valid code
        abort_if($booking->code != $code, 404);

        $booking->load('latestPayment', 'room');

        $diff = $this->diffOfDate($booking->latestPayment->room_type_price, $booking->from_date->startOfDay(), $booking->until_date->startOfDay());

        return view('customer.pages.payment.form', compact('booking', 'diff'));
    }
}
