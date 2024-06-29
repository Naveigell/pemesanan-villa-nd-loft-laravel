@extends('layouts.customer.customer')

@section('content-body')
    @include('layouts.customer.section', [
        'text' => '<h1 class="heading">Choose Your Room</h1>'
    ])
    <!-- END section -->

    <section class="section pb-4">
        <div class="container">

            <div class="row check-availabilty" id="next">
                <div class="block-32" data-aos="fade-up" data-aos-offset="-200">
                    <form action="{{ route('rooms.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-lg-4 col-lg-4">
                                <label for="checkin_date" class="font-weight-bold text-black">Check In</label>
                                <div class="field-icon-wrap">
                                    <div class="icon"><span class="icon-calendar"></span></div>
                                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4 mb-lg-4 col-lg-4">
                                <label for="checkout_date" class="font-weight-bold text-black">Check Out</label>
                                <div class="field-icon-wrap">
                                    <div class="icon"><span class="icon-calendar"></span></div>
                                    <input type="date" name="until" class="form-control" value="{{ request('until') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 align-self-center" style="transform: translateY(13%);">
                                <button class="btn btn-primary btn-block text-white" type="submit">Cek Ketersediaan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($message = session('success'))
            <script>alert('{{ $message }}');</script>
        @endif
    </section>


    <section class="section">
        <div class="container">

            <div class="row">
                @foreach($rooms as $room)
                    <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up">
                        <a href="{{ route('reservations.create', $room) . '?' . request()->getQueryString() }}" class="room">
                            <figure class="img-wrap">
                                <img src="{{ $room->main_image_url }}" alt="Free website template" class="img-fluid mb-3">
                            </figure>
                            <div class="p-3 text-center room-info">
                                <h2>Kamar - {{ $room->name }}</h2>
                                <span class="text-uppercase letter-spacing-1">{{ $room->price_formatted }} / per malam</span>
                                <div style="font-size: 12px;" class="mt-3">
                                    <label>Fasilitas: </label>
                                    @foreach($room->facilities as $facility)
                                        <span class="d-inline-block border px-2">{{ $facility->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
