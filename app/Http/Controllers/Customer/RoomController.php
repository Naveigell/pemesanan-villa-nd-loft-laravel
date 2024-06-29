<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $from  = $request->query('from');
        $until = $request->query('until');

        $rooms = [];

        if ($from && $until) {
            $from = Carbon::parse($from)->startOfDay(); // set time to 00:00:00 to prevent calculation bugs in the future
            $until   = Carbon::parse($until)->startOfDay();

            // abort if from date is greater than to date
            abort_if($from->gt($until), 404);

            // add one day in the end because we want to include the last day
            $periods = new \DatePeriod($from->toDate(), new \DateInterval('P1D'), $until->addDay()->toDate());
            $periods = collect($periods)->map(fn($period) => $period->format('Y-m-d'))->toArray(); // looping all the period and format it into Y-m-d

            $rooms = Room::with('image', 'facilities')
                ->whereNotIn('id', Booking::whereMultipleBookedAt($periods, ['room_id'])->groupBy('room_id'))
                ->get();
        }

        return view('customer.pages.room.index', compact('rooms'));
    }

    public function show(Request $request, Room $room)
    {
        dd($room);
    }
}
