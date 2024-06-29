<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReservationRequest;
use App\Jobs\SendCustomerInvoiceJob;
use App\Models\Booking;
use App\Models\Room;
use App\Utils\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ReservationController extends Controller
{
    /**
     * A description of the entire PHP function.
     *
     * @param Request $request description
     * @param Room $room description
     */
    public function create(Request $request, Room $room)
    {
        $from  = $request->query('from');
        $until = $request->query('until');

        // the page should contain from and until query string, and until should greater than from
        abort_if((!$from || !$until) && $from > $until, 404);

        // we don't want the booking page is accessible if the room is already booked
        $isBookingExists = Booking::whereDateInsideRange($from, $until)->where('room_id', $room->id)->doesntExist();

        abort_if(!$isBookingExists, 404, 'Room already booked');

        $room->loadMissing('facilities');

        return view('customer.pages.reservation.form', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReservationRequest $request
     * @param Room $room
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReservationRequest $request, Room $room)
    {
        $from  = $request->query('from');
        $until = $request->query('until');

        // the page should contain from and until query string, and until should greater than from
        abort_if((!$from || !$until) && $from > $until, 404);

        DB::beginTransaction();

        try {
            $booking = new Booking($request->validated());
            $booking->room()->associate($room);
            $booking->generateBookingCode();
            $booking->save();

            $items = $room->only('id', 'name', 'code'); // remember every item should have id
            $items['price'] = $room->price_integer; // just take formatted price because the real price attributes is float (can't input float)
            $items['quantity'] = 1;

            $customer = [
                "first_name" => $booking->customer_name,
                "email"      => $booking->customer_email,
                "phone"      => $booking->customer_phone,
            ];

            $midtrans = new Midtrans([
                "order_id"     => $booking->code,
                "gross_amount" => $room->price,
            ], [$items], $customer);

            $booking->payment()->create([
                "snap_token" => $midtrans->snapToken(),
                "payload"    => json_encode($midtrans->payload()),
            ]);

            DB::commit();

            dispatch(new SendCustomerInvoiceJob($booking));
        } catch (\Exception $exception) {
            DB::rollBack();

            dd($exception->getMessage());
        }

        return redirect(route('rooms.index'))->with('success', 'Berhasil memesan kamar, mohon melihat email anda untuk pembayaran');
    }
}
