@extends('layouts.admin.admin')

@section('content-title', 'Kamar')

@section('content-body')
    <div class="col-12 col-md-12 col-lg-12 no-padding-margin">
        <div class="card">
            <form action="{{ @$room ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method(@$room ? 'PUT' : 'POST')
                <div class="card-header">
                    <h4>Form Kamar</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Kamar</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', @$room ? $room->code : '') }}">
                        @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Kamar</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', @$room ? $room->name : '') }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Harga Kamar</label>
                        <div class="form-group">
                            @php
                                $priceTypes = \App\Enums\RoomPriceTypeEnum::cases()
                            @endphp
                            @foreach($priceTypes as $type)
                                <div class="input-group mt-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $type->label() }}
                                        </div>
                                    </div>
                                    <input type="text" class="form-control price @error('prices.' . $type->value) is-invalid @enderror" name="prices[{{ $type->value }}]" value="{{ old('prices.' . $type->value, @$room ? round($room->prices->where('type', $type)->first()->price) : '') }}">
                                    @error('prices.' . $type->value)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Warna Kamar</label>
                        <input type="color" class="form-control price @error('color') is-invalid @enderror" name="color" value="{{ old('color', @$room ? $room->color : '') }}">
                        @if($errors->has('color'))
                            <div class="invalid-feedback">
                                {{ $errors->first('color') }}
                            </div>
                        @else
                            <small class="text text-muted">Digunakan untuk menandai warna kamar pada menu kalender</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="control-label">Fasilitas</div>
                        <div class="row">
                            @foreach($facilities as $lists)
                                <div class="col-6">
                                    @foreach($lists as $facility)
                                        @php
                                            // check if room has this facility by intersect the id
                                            $hasThisFacility = @$room && $room->facilities->pluck('id')->intersect($facility->id)->isNotEmpty();
                                        @endphp
                                        <label class="custom-switch mt-2 p-0 d-block">
                                            <input type="checkbox" @if($hasThisFacility) checked @endif name="facilities[]" value="{{ $facility->id }}" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ $facility->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
