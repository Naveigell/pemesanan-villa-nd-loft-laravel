<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
{
    /**
     * @var string from date
     */
    private string $from;

    /**
     * @var string to date
     */
    private string $to;

    /**
     * @var string add Rp in front of number
     */
    private const FORMAT_ACCOUNTING_RP_INDONESIAN = '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)';

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        [$from, $to] = [$this->from, $this->to];

        $bookings = Booking::with('latestPayment', 'room')
            ->whereHas('latestPayment', function ($query) use ($from, $to) {
                $query->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereBetween('payments.paid_at', [$from, $to]);
                });
            })
            ->latest()
            ->get()
            ->map(function (Booking $booking) {
                return [
                    "Kode"            => $booking->code,
                    "Nama Pemesan"    => $booking->customer_name,
                    "Kamar"           => $booking->room->name,
                    "Telp"            => $booking->customer_phone,
                    "Email"           => $booking->customer_email,
                    "Alamat"          => $booking->customer_address,
                    "Dari Tanggal"    => $booking->from_date->format('d F Y'),
                    "Sampai Tanggal"  => $booking->until_date->format('d F Y'),
                    "Total Harga"     => $booking->latestPayment->gross_amount,
                    "Pembayaran"      => $booking->latestPayment->paymentTypeDetail() ?? 'Belum ada',
                    "Durasi"          => $booking->latestPayment->item_duration . ' ' . $booking->latestPayment->room_type_price->label(),
                    "Tanggal Dibayar" => $booking->latestPayment->paid_at->format('d F Y'),
                ];
            });

        return $bookings;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            "D" => "+#",
            "I" => self::FORMAT_ACCOUNTING_RP_INDONESIAN,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode',
            'Nama Pemesan',
            'Kamar',
            'Telp',
            'Email',
            'Alamat',
            'Dari Tanggal',
            'Sampai Tanggal',
            'Total Harga',
            'Pembayaran',
            'Durasi',
            'Tanggal Dibayar',
        ];
    }
}
