<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $dates = [now()->toDateString(), now()->addDay()->toDateString()];

        $rooms = Room::with('image', 'facilities')
            ->whereNotIn('id', Booking::whereMultipleBookedAt($dates, ['room_id']))
            ->get();

        return view('customer.pages.home.index', compact('rooms'));
    }
}
