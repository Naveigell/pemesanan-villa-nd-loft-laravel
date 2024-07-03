<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Admin\BookingCalendarCollection;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('month', date('m'));
        // create date by it month and current year
        $date = mktime(0, 0, 0, $date, 1, date('Y'));
        $date = Carbon::createfromtimestamp($date);

        // get previous, current and next month, previous month by subtracting month, and next month by adding month
        $previousDate = $date->clone()->subMonth()->month;
        $currentDate = $date->month;
        $nextDate = $date->clone()->addMonth()->month;

        $bookings = Booking::with('room', 'roomPrice')
            ->whereIn(DB::raw('MONTH(from_date)'), [$previousDate, $currentDate, $nextDate])
            ->where('status', BookingStatus::APPROVED->value)
            ->get();

        return new BookingCalendarCollection($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
