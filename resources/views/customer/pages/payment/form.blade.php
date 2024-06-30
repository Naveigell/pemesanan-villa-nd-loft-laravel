<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pembayaran dan Booking</title>

    <link rel="stylesheet" href="{{ asset('assets/customer/css/bootstrap.min.css') }}">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Detail Pemesanan</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td>Nomor Transaksi</td>
                    <td>: {{ $booking->code }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>: {{ $booking->customer_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: {{ $booking->customer_email }}</td>
                </tr>
                <tr>
                    <td>No Telp</td>
                    <td>: {{ $booking->customer_phone }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $booking->from_date->format('d F Y') }} - {{ $booking->until_date->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Kamar</td>
                    <td>: {{ $booking->room->name }}</td>
                </tr>
                <tr>
                    <td>Harga</td>
                    <td>: {{ $booking->room->price_formatted }}</td>
                </tr>
                <tr>
                    <td>Status Pembayaran</td>
                    <td>: {!! optional($booking->latestPayment->transaction_status)->toHtmlBadge() ?? '-' !!}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
