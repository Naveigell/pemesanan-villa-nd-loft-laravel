<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatusEnum;
use App\Enums\Filters\Reports\UsingEnum;
use App\Exports\BookingExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $bookings = $this->constructQuery()->paginate(10);

        return view('admin.pages.report.index', compact('bookings'));
    }

    public function create()
    {
        $from = request('from');
        $to   = request('to');

        abort_if(!$from || !$to || $this->constructQuery()->doesntExist() /** if query is empty **/, 404);

        $fromString = date('d F Y', strtotime($from));
        $toString   = date('d F Y', strtotime($to));
        $uniqueId   = uniqid();

        return Excel::download(new BookingExport($this->constructQuery()), "Laporan dari tanggal {$fromString} s.d {$toString} - {$uniqueId}.xlsx");
    }

    /**
     * Constructs the query to retrieve bookings with the latest payment and room information.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function constructQuery()
    {
        $from = request('from');
        $to   = request('to');

        $status = BookingStatusEnum::tryFrom(request('status'));
        $using  = UsingEnum::tryFrom(request('using'));

        return Booking::with('latestPayment', 'room')
            ->whereHas('latestPayment', function ($query) use ($from, $to, $using) {
                // when using paid date and from to is set
                $query->when($from && $to && $using && $using->isPaidDate(), function ($query) use ($from, $to) {
                    $query->whereBetween('payments.paid_at', [$from, $to]);
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status->value);
            })
            // when using booking date and from to is set
            ->when($from && $to && $using && $using->isBookingDate(), function ($query) use ($from, $to) {
                // check if from_date date is between from and to, OR
                // until_date date is between from and to
                $query->where(function ($query) use ($from, $to) {
                    $query->whereBetween('bookings.from_date', [$from, $to]);
                    $query->orWhereBetween('bookings.until_date', [$from, $to]);
                });
            })
            ->latest();
    }
}
