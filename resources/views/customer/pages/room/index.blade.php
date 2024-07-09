@extends('layouts.customer.customer')

@section('content-body')
    @include('layouts.customer.section', [
        'text' => '<h1 class="heading">Choose Your Room</h1>'
    ])
    <!-- END section -->

    @include('admin.shared.date.date_chooser')

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
