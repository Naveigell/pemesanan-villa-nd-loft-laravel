<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BookingExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $from = request('from');
        $to   = request('to');

        $bookings = Booking::with('latestPayment', 'room')
            ->whereHas('latestPayment', function ($query) use ($from, $to) {
                $query->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereBetween('payments.paid_at', [$from, $to]);
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.pages.report.index', compact('bookings'));
    }

    public function create()
    {
        $from = request('from');
        $to   = request('to');

        abort_if(!$from || !$to, 404);

        $fromString = date('d F Y', strtotime($from));
        $toString   = date('d F Y', strtotime($to));
        $uniqueId   = uniqid();

        return Excel::download(new BookingExport($from, $to), "Laporan dari tanggal {$fromString} s.d {$toString} - {$uniqueId}.xlsx");
    }
}
