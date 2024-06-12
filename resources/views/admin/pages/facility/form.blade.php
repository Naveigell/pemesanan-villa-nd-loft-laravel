@extends('layouts.admin.admin')

@section('content-title', 'Fasilitas')

@section('content-body')
    <div class="col-12 col-md-12 col-lg-12 no-padding-margin">
        <div class="card">
            <form action="{{ @$facility ? route('admin.facilities.update', $facility) : route('admin.facilities.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method(@$facility ? 'PUT' : 'POST')
                <div class="card-header">
                    <h4>Form Fasilitas</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Fasilitas</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', @$facility ? $facility->name : '') }}">
                        @error('name')
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
