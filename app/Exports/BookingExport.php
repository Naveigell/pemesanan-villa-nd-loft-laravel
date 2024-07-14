<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
{
    /**
     * @var Builder
     */
    private Builder $query;

    /**
     * @var string add Rp in front of number
     */
    private const FORMAT_ACCOUNTING_RP_INDONESIAN = '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)';

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query
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
