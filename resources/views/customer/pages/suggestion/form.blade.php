@extends('layouts.customer.customer')

@section('content-body')
    <section class="section">
        <div class="container">
            @if ($message = session()->get('success'))
                <x-alert.success :message="$message"></x-alert.success>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Saran</h4>
                </div>
                <div class="card-body" @if(!@$suggestion) style="height: 300px; @endif">
                    @if(@$suggestion)
                        @foreach($suggestion->details as $detail)
                            @if($detail->isFromItSelf())
                                <div class="d-flex justify-content-end align-items-center">
                                    <div>
                                        <span class="d-block">Dari : <span class="badge badge-warning">anda</span></span>
                                        <span class="alert alert-warning d-block mt-1">{{ $detail->message }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-start align-items-center">
                                    <div>
                                        <span class="d-block">Dari : <span class="badge badge-dark">{{ $detail->user->type()->value }}</span></span>
                                        <span class="alert alert-dark d-block mt-1">{{ $detail->message }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ @$suggestion ? route('customer.suggestions.update', $suggestion) : route('customer.suggestions.store') }}" class="row" method="post">
                        @csrf
                        @method(@$suggestion ? 'PUT' : 'POST')
                        <div class="col-10">
                            <input type="text" class="form-control @error('message') is-invalid @enderror" name="message" placeholder="Pesan ...">
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
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

