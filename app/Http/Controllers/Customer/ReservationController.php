<?php

namespace App\Http\Controllers\Customer;

use App\Enums\RoomPriceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReservationRequest;
use App\Jobs\SendCustomerInvoiceJob;
use App\Models\Booking;
use App\Models\Room;
use App\Traits\CanFormatDateTimeByType;
use App\Utils\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    use CanFormatDateTimeByType;

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
        $type  = $request->query('type');
        $type  = RoomPriceTypeEnum::tryFrom($type);

        // the page should contain from and until query string, and until should greater than from
        abort_if((!$from || !$until || !$type) && $from > $until, 404);

        // format date time
        $date = $this->formatDateTime($type, $from, $until);

        $from  = $date->from;
        $until = $date->until;
        $diff  = $this->diffOfDate($type, $from, $until);

        // we don't want the booking page is accessible if the room is already booked
        $isBookingExists = Booking::whereDateInsideRange($from, $until)->where('room_id', $room->id)->doesntExist();

        abort_if(!$isBookingExists, 404, 'Room already booked');

        $room->loadMissing([
            'facilities',
            'price' => function ($query) use ($type) {
                $query->where('type', $type->value);
            }
        ]);

        return view('customer.pages.reservation.form', compact('room', 'type', 'diff'));
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
        $type  = $request->query('type');
        $type  = RoomPriceTypeEnum::tryFrom($type);

        // the page should contain from and until query string, and until should greater than from
        abort_if((!$from || !$until || !$type) && $from >= $until, 404);

        // always format date time
        $date = $this->formatDateTime($type, $from, $until);

        $from  = $date->from;
        $until = $date->until;

        DB::beginTransaction();

        try {
            $booking = new Booking($request->validated());
            $booking->room()->associate($room);
            $booking->generateBookingCode();
            $booking->save();

            // get room price by type
            $roomPrice = $room->prices()->where('type', $type->value)->first();
            $diff      = $this->diffOfDate($type, $from, $until);

            // connect the room price and booking
            $booking->roomPrice()->save($roomPrice);

            // get total price by multiplying room price and diff between today and until
            // just take formatted price because the real price attributes is float (can't input float)
            $totalPrice = $roomPrice->price_integer * $diff;

            $items = $room->only('id', 'name', 'code'); // remember every item should have id
            $items['price']           = $totalPrice;
            $items['diff']            = $diff;
            $items['quantity']        = 1;
            $items['room_price_type'] = $type->value;

            $customer = [
                "first_name" => $booking->customer_name,
                "email"      => $booking->customer_email,
                "phone"      => $booking->customer_phone,
            ];

            $midtrans = new Midtrans([
                "order_id"     => $booking->code,
                "gross_amount" => $totalPrice,
            ], [$items], $customer);

            $booking->payment()->create([
                "snap_token" => $midtrans->snapToken(),
                "payload"    => json_encode($midtrans->payload()),
            ]);

            // load the room price by its type
            $booking->loadMissing([
                'room.price' => function ($query) use ($type) {
                    $query->where('type', $type->value);
                }
            ]);

            DB::commit();

            dispatch(new SendCustomerInvoiceJob($booking, $type, $totalPrice));
        } catch (\Exception $exception) {
            DB::rollBack();

            dd($exception->getMessage());
        }

        return redirect(route('rooms.index'))->with('success', 'Berhasil memesan kamar, mohon melihat email anda untuk pembayaran');
    }
}
