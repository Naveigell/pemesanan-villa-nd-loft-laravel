@extends('layouts.admin.admin')

@section('content-title', 'Saran')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Saran dari - {{ @$suggestion->user->name }}</h4>
        </div>
        <div class="card-body" @if(!@$suggestion) style="height: 300px; @endif">
            @if(@$suggestion)
                @foreach($suggestion->details as $detail)
                    <div class="mt-2">
                        @if($detail->isFromItSelf())
                            <div class="d-flex justify-content-end align-items-center">
                                <div>
                                    <span class="d-block">Dari : <span class="badge badge-primary">anda</span></span>
                                    <span class="alert alert-primary d-block mt-2">{{ $detail->message }}</span>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-start align-items-center">
                                <div>
                                    <span class="d-block">Dari : <span class="badge badge-light">{{ $detail->user->type()->value }}</span></span>
                                    <span class="alert alert-light d-block mt-2">{{ $detail->message }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
        <div class="card-footer" style="border-top: 1px solid #eee8d5;">
            <form action="{{ @$suggestion ? route('admin.suggestions.update', $suggestion) : route('admin.suggestions.store') }}" class="row" method="post">
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
                    <button class="btn btn-warning w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
