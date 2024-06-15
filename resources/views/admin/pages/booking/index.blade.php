@extends('layouts.admin.admin')

@section('content-title', 'Booking')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 no-padding-margin">
        <div class="card">
            <div class="card-header">
                <h4>Booking</h4>
                @if(auth()->user()->isAdmin())
                    <div class="card-header-action">
                        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kamar</a>
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Kode Transaksi</th>
                            <th class="col-1">Nama Customer</th>
                            <th class="col-1">Email Customer</th>
                            <th class="col-1">No Telp Customer</th>
                            <th class="col-1">Tanggal Booking</th>
                            <th class="col-1">Status</th>
                            <th class="col-1">Pembayaran</th>
                            <th class="col-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$bookings" :loop="$loop"></x-iterate>
                                </td>
                                <td class="py-3">{{ $booking->code }}</td>
                                <td>{{ $booking->customer_name }}</td>
                                <td>{{ $booking->customer_email }}</td>
                                <td>{{ $booking->customer_phone }}</td>
                                <td><span class="badge badge-light d-block">{{ $booking->from_date->format('d F Y') }}</span> <span class="d-block text-center py-1">s/d.</span> <span class="badge badge-light d-block">{{ $booking->until_date->format('d F Y') }}</span></td>
                                <td>{!! $booking->status->toHtmlBadge() !!}</td>
                                <td class="py-3">
                                    @if ($booking->latestPayment)
                                        <a href="{{ $booking->latestPayment->payment_proof_image_url }}" class="image-zoom">
                                            <img src="{{ $booking->latestPayment->payment_proof_image_url }}" style="width: 150px; height: 150px;" alt="">
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                                    <button class="btn btn-danger btn-action trigger--modal-delete cursor-pointer" data-url="{{ route('admin.bookings.destroy', $booking) }}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">Data Empty</td>
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

@section('content-modal')
    <x-modal.delete name="Booking"></x-modal.delete>
@endsection
