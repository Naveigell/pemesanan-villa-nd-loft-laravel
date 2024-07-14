@extends('layouts.customer.customer')

@section('content-body')
    <section class="section">
        <div class="container">
            <a href="{{ route('customer.suggestions.create') }}" class="btn btn-primary" style="border-radius: 3px;">Buat Saran</a>
            <div class="row mt-4">
                @php
                    $total = $suggestions->count();
                @endphp
                @foreach($suggestions as $suggestion)
                    <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up">
                        <div class="card">
                            <div class="card-header">
                                <h4><span class="d-block">Saran ke - {{ abs($loop->index - $total) }}</span><span class="d-block mt-1" style="font-size: 12px;">Dibuat tanggal : {{ $suggestion->created_at->format('d-m-Y') }}</span></h4>
                            </div>
                            <div class="card-body">
                                <div>
                                    <span>Balasan terakhir dari : {!! $suggestion->latestDetail->user->type()->toHtmlBadge() !!}</span>
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <p>{{ $suggestion->latestDetail->message }}</p>
                                        </div>
                                    </div>
                                    @if($suggestion->details_count > 0)
                                        <span class="text text-muted d-block mt-2" style="font-size: 12px;">Ada {{ max(0, $suggestion->details_count - 1) }} pesan lain.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('customer.suggestions.edit', $suggestion) }}" class="btn btn-primary btn-sm" style="border-radius: 12px; padding: 7px 15px;">Lihat</a>
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
