@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        table, td, div, h1, p {font-family: Arial, sans-serif;}
    </style>
</head>
<body style="margin:0;padding:0;">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:40px 0;">
            <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                <tr>
                    <td style="padding:30px;background:#525252;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                                <td style="padding:0;width:50%;" align="left">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 30px 42px 30px;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0 0 36px 0;color:#153643;">
                                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Pemesanan Kamar Berhasil</h1>
                                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Berikut adalah data transaksi.</p>
                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Nomor Transaksi</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->code }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Nama</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Email</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->customer_email }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">No Telp</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->customer_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Tanggal</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->from_date->format('d F Y') }} - {{ $booking->until_date->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Kamar</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $booking->room->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Total Harga</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ format_price($totalPrice) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:30%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Total {{ $type->label() }}</td>
                                            <td style="width:70%;padding:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">: {{ $diff }} {{ $type->label() }}</td>
                                        </tr>
                                    </table>
                                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Dimohon untuk melakukan pembayaran melalui link berikut ini
                                        <a href="{{ optional($booking->latestPayment)->payment_url }}" class="" target="_blank">link</a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px;background:#ee4c50;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                                <td style="padding:0;width:50%;" align="left">
                                    <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                        &reg; Villa Nd Loft
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
