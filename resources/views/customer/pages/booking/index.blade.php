@extends('layouts.customer.customer')

@section('content-body')
    <section class="section">
        <div class="container">
            <div class="row">
                @foreach($bookings as $booking)
                    <div class="col-4 mb-4 pb-0">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kamar - {{ $booking->room->name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nomor Transaksi</label>
                                    <input type="text" disabled class="form-control"
                                           value="{{ $booking->code }}">
                                </div>
                                <div class="form-group">
                                    <label>Nama Pemesan</label>
                                    <input type="text" disabled class="form-control"
                                           value="{{ $booking->customer_name }}">
                                </div>
                                <div class="form-group">
                                    <label>Email Pemesan</label>
                                    <input type="text" disabled class="form-control"
                                           value="{{ $booking->customer_email }}">
                                </div>
                                <div class="form-group">
                                    <label>No Telp Pemesan</label>
                                    <input type="text" disabled class="form-control"
                                           value="{{ $booking->customer_phone }}">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Booking</label>
                                    <input type="text" disabled class="form-control"
                                           value="{{ $booking->from_date->format('d F Y') }} s.d {{ $booking->until_date->format('d F Y') }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label>Status Booking : </label>
                                    <span>{!! $booking->status->toHtmlBadge() !!}</span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-0 d-block">Status Pembayaran </label>
                                    @if(optional($booking->latestPayment)->transaction_status)
                                        <span
                                            class="d-inline-block">{!! $booking->latestPayment->transaction_status->toHtmlBadge() !!}</span>
                                        @if($booking->latestPayment->transaction_status)
                                            <span
                                                class="badge badge-dark d-inline-block">{{ $booking->latestPayment->paymentTypeDetail() }}</span>
                                        @endif
                                    @else
                                        <span>-</span>
                                    @endif
                                </div>
                                {{-- if transaction is pending or it doesn't have any status--}}
                                @if(optional($booking->latestPayment->transaction_status)->isPending() || !optional($booking->latestPayment)->transaction_status)
                                    <div class="form-group">
                                        <a href="{{ str_replace(':token', $booking->latestPayment->snap_token, config('midtrans.development_redirect_url')) }}"
                                           target="_blank" class="btn btn-primary btn-sm">Bayar</a>
                                    </div>
                                @elseif(optional($booking->latestPayment->transaction_status)->isSettlement())
                                    <div class="form-group">
                                        {{--                                        <a href="{{ route('customer.bookings.show', $booking) }}" target="_blank" class="btn btn-success btn-sm">Lihat Transaksi</a>--}}
                                        <a href="{{ $booking->constructDetailPageUrl() }}" target="_blank"
                                           class="btn btn-success btn-sm">Lihat Transaksi</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('stack-script')
    <script>
        $('.js-site-header').addClass('scrolled');
        $('.js-site-header').removeClass('js-site-header');
    </script>
@endpush
