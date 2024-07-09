<?php

namespace App\Http\Controllers\Customer;

use App\Enums\RoomPriceTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Traits\CanFormatDateTimeByType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use CanFormatDateTimeByType;

    public function index(Request $request)
    {
        $from  = $request->query('from');
        $until = $request->query('until');
        $type  = $request->query('type');
        $type  = RoomPriceTypeEnum::tryFrom($type);

        $rooms = [];

        if ($from && $until && $type) {
            // format date time
            $date = $this->formatDateTime($type, $from, $until);

            $from = $date->from;
            $until = $date->until;

            // abort if from date is greater than to date
            abort_if($from->gt($until), 404);

            // add one day in the end because we want to include the last day
            $periods = new \DatePeriod($from->toDate(), new \DateInterval('P1D'), $until->toDate());
            $periods = collect($periods)->map(fn($period) => $period->format('Y-m-d'))->toArray(); // looping all the period and format it into Y-m-d

            $rooms = Room::with('image', 'facilities')
                ->whereNotIn('id', Booking::whereMultipleBookedAt($periods, ['room_id'])->groupBy('room_id'))
                ->get();
        }

        return view('customer.pages.room.index', compact('rooms'));
    }
}
