@extends('layouts.admin.admin')

@section('content-title', 'Laporan')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 no-padding-margin">
        <div class="card">
            <div class="card-header">
                <h4>Laporan</h4>
            </div>
            <div class="card-body p-0">
                <form action="" method="get" class="mt-1">
                    <div class="form-row container">
                        <div class="form-group col-6">
                            <label for="from">Tanggal</label>
                            <input type="text" id="daterangepicker" class="daterangepicker--filter form-control"/>

                            <input type="hidden" id="from" name="from" value="{{ request('from') }}">
                            <input type="hidden" id="to" name="to" value="{{ request('to') }}">
                        </div>
                        <div class="form-group col-12">
                            <button class="btn btn-success btn-md">Filter</button>
                            @if($bookings->isNotEmpty())
                                <a href="{{ route('admin.reports.create', ['from' => request('from'), 'to' => request('to')]) }}" class="btn btn-primary btn-md"><i class="fa fa-download"></i> &nbsp;Download</a>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Kode Booking</th>
                            <th class="col-1">Kamar</th>
                            <th class="col-1">Nama Pengunjung</th>
                            <th class="col-1">No Telp Pengunjung</th>
                            <th class="col-1">Tanggal Booking</th>
                            <th class="col-1">Status</th>
                            <th class="col-1">Tanggal Pembayaran</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$bookings" :loop="$loop"></x-iterate>
                                </td>
                                <td><a href="{{ route('admin.bookings.edit', $booking) }}" target="_blank">{{ $booking->code }}</a></td>
                                <td class="py-3">{{ $booking->room->name }}</td>
                                <td class="py-3">{{ $booking->customer_name }}</td>
                                <td>{{ $booking->customer_phone }}</td>
                                <td class="py-2">
                                    <span class="badge badge-light d-block">{{ $booking->from_date->format('d F Y') }}</span>
                                    <span class="d-block text-center py-1">s/d.</span>
                                    <span class="badge badge-light d-block">{{ $booking->until_date->format('d F Y') }}</span>
                                </td>
                                <td>{!! $booking->status->toHtmlBadge() !!}</td>
                                <td>
                                    @if (optional($booking->latestPayment)->transaction_status)
                                        {{ $booking->latestPayment->paid_at->format('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center;">Data Empty</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stack-script')
    <script>
        $('.daterangepicker--filter').daterangepicker({
            locale: {
                format: 'DD MMMM YYYY',
                separator: '   s.d   ',
            },
            drops: 'down',
            opens: 'right'
        }, function(start, end, label) {
            $('#from').val(start.format('YYYY-MM-DD'));
            $('#to').val(end.format('YYYY-MM-DD'));
        });
    </script>

    @if(request()->query('from') && request()->query('to'))
        <script>
            $('#daterangepicker').data('daterangepicker').setStartDate(moment($('#from').val(), 'YYYY-MM-DD'));
            $('#daterangepicker').data('daterangepicker').setEndDate(moment($('#to').val(), 'YYYY-MM-DD'));
        </script>
    @endif
@endpush

@section('content-modal')
    <x-modal.delete name="Laporan"></x-modal.delete>
@endsection
