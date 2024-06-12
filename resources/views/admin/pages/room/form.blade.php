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
                        <input type="text" class="form-control price @error('price') is-invalid @enderror" name="price" value="{{ old('price', @$room ? round($room->price) : '') }}">
                        @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
