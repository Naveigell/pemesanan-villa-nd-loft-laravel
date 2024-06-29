@extends('layouts.customer.customer')

@section('content-body')
    @include('layouts.customer.section', [
        'text' => '<h1 class="heading">Reservasi Kamar ' . $room->name . '</h1>'
    ])
    <!-- END section -->

    <section class="section contact-section" id="next">
        <div class="container">
            <div class="row">
                <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">
                    <form action="{{ route('reservations.store', $room) . '?' . request()->getQueryString() }}" method="post" class="bg-white p-md-5 p-4 mb-5 border">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="text-black font-weight-bold" for="name">Nama</label>
                                <input type="text" id="name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" name="customer_name">
                                @error('customer_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="text-black font-weight-bold" for="phone">No Telp</label>
                                <input type="text" id="phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" name="customer_phone">
                                @error('customer_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="text-black font-weight-bold" for="email">Email</label>
                                <input type="email" id="email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" name="customer_email">
                                @error('customer_email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="text-black font-weight-bold" for="address">Alamat</label>
                                <input type="text" id="address" class="form-control @error('customer_address') is-invalid @enderror" value="{{ old('customer_address') }}" name="customer_address">
                                @error('customer_address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="text-black font-weight-bold" for="checkin_date">Date Check In</label>
                                <input type="date" class="form-control" value="{{ request('from') }}" readonly name="from_date">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="text-black font-weight-bold" for="checkout_date">Date Check Out</label>
                                <input type="date" class="form-control" value="{{ request('until') }}" readonly name="until_date">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="facilities" class="d-inline-block text-black font-weight-bold">Fasilitas : </label>
                                <br>
                                @foreach($room->facilities as $facility)
                                    <span class="d-inline-block border px-3">{{ $facility->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mb-4 mt-2">
                            <div class="col-md-12 form-group">
                                <label class="text-black font-weight-bold" for="price">Harga</label>
                                <input type="text" id="price" class="form-control" name="price" disabled value="{{ $room->price_formatted }} / malam">
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-12 form-group">
                                <label class="text-black font-weight-bold" for="message">Notes</label>
                                <textarea name="notes" id="message" class="form-control @error('notes') is-invalid @enderror" cols="30" rows="8">{{ old('notes') }}</textarea>
                                @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="submit" value="Pesan Sekarang" class="btn btn-primary text-white py-3 px-5 font-weight-bold">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-md-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="row">
                        <div class="col-md-10 ml-auto contact-info">
                            <p><span class="d-block">Alamat:</span> <span class="text-black">{{ config('detail.address') }}</span></p>
                            <p><span class="d-block">Phone:</span> <span class="text-black"> {{ config('detail.phone') }}</span></p>
                            <p><span class="d-block">Email:</span> <span class="text-black"> {{ config('detail.email') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
