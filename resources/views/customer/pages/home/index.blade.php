@extends('layouts.customer.customer')

@section('content-body')
    @include('layouts.customer.section', [
        'text' => '<span class="custom-caption text-uppercase text-white d-block  mb-3">Selamat Datang di bintang 5 <span class="fa fa-star text-primary"></span>   Villa</span>
            <h1 class="heading">ND Loft Villa</h1>'
    ])
    <!-- END section -->

    @include('admin.shared.date.date_chooser')

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-7 ml-auto order-lg-2 position-relative mb-5" data-aos="fade-up">
                    <img src="{{ asset('assets/customer/images/img_1.jpg') }}" alt="Image" class="img-fluid rounded">
                </div>
                <div class="col-md-12 col-lg-4 order-lg-1" data-aos="fade-up">
                    <h2 class="heading">Selamat datang!</h2>
                    <p class="mb-4">Villa Nd Loft menawarkan kenyamanan dan pelayanan terbaik. Kami menawarkan berbagai macam fasilitas. Anda dapat memilih berbagai kamar dengan fasilitas yang ada tentunya dengan harga terjangkau.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-md-7">
                    <h2 class="heading" data-aos="fade-up">Kamar dan fasilitas</h2>
                    <p data-aos="fade-up" data-aos-delay="100">Pilihlah kamar yang sesuai dengan kebutuhan anda. Berikut adalah kamar yang tersedia di villa pada tanggal <b class="font-weight-bold"><u>hari ini</u></b> dan <b class="font-weight-bold"><u>esok hari</u></b>.</p>
                </div>
            </div>
            <div class="row">
                @foreach($rooms as $room)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <a href="{{ route('reservations.create', $room) . '?' . http_build_query(['from' => now()->toDateString(), 'until' => now()->addDay()->toDateString(), 'type' => \App\Enums\RoomPriceTypeEnum::DAY->value]) }}" class="room">
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

    <!-- END section -->
    <section class="section testimonial-section bg-light">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-md-7">
                    <h2 class="heading" data-aos="fade-up">Testimoni</h2>
                </div>
            </div>
            <div class="row">
                <div class="js-carousel-2 owl-carousel mb-5" data-aos="fade-up" data-aos-delay="200">

                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_1.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>

                            <p>&ldquo;A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; Jean Smith</em></p>
                    </div>

                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_2.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>
                            <p>&ldquo;Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; John Doe</em></p>
                    </div>

                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_3.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>

                            <p>&ldquo;When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; John Doe</em></p>
                    </div>


                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_1.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>

                            <p>&ldquo;A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; Jean Smith</em></p>
                    </div>

                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_2.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>
                            <p>&ldquo;Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; John Doe</em></p>
                    </div>

                    <div class="testimonial text-center slider-item">
                        <div class="author-image mb-3">
                            <img src="{{ asset('assets/customer/images/person_3.jpg') }}" alt="Image placeholder" class="rounded-circle mx-auto">
                        </div>
                        <blockquote>

                            <p>&ldquo;When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.&rdquo;</p>
                        </blockquote>
                        <p><em>&mdash; John Doe</em></p>
                    </div>

                </div>
                <!-- END slider -->
            </div>

        </div>
    </section>
@endsection
