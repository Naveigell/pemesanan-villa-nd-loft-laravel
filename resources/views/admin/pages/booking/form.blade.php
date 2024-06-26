@extends('layouts.admin.admin')

@section('content-title', 'Booking')

@section('content-body')
    <div class="row">
        <div class="col-lg-7 col-md-7 col-7 col-sm-12 no-padding-margin">
            <div class="card">
                <div class="card-header">
                    <h4>Pemesanan</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Pemesanan</label>
                        <input type="text" disabled class="form-control" value="{{ @$booking ? $booking->code : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" disabled class="form-control" value="{{ @$booking ? $booking->customer_name : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Email Customer</label>
                        <input type="text" disabled class="form-control" value="{{ @$booking ? $booking->customer_email : '' }}">
                    </div>
                    <div class="form-group">
                        <label>No Telp Customer</label>
                        <input type="text" disabled class="form-control" value="{{ @$booking ? $booking->customer_phone : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Customer</label>
                        <input type="text" disabled class="form-control" value="{{ @$booking ? $booking->customer_address : '' }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h4>Pembayaran Terakhir</h4>
                </div>
                <div class="card-body">
                    @if(!$booking->latestPaidPayment)
                        <x-alert.danger message="Belum ada pembayaran" :dismissable="false"></x-alert.danger>
                    @else
                        @if ($message = session()->get('payment-success'))
                            <x-alert.success :message="$message"></x-alert.success>
                        @endif
                        <form class="" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Tipe Pembayaran</label>
                                <input type="text" disabled class="form-control" value="{{ @$booking ? optional($booking->latestPayment)->paymentTypeDetail() : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Total Pembayaran</label>
                                <input type="text" disabled class="form-control" value="{{ @$booking ? format_price(optional($booking->latestPayment)->response_array['gross_amount']) : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <input type="text" disabled class="form-control" value="{{ @$booking ? optional($booking->latestPayment)->transaction_status->label() : '' }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
