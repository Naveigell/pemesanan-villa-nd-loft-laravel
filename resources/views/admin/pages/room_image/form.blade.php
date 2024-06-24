@extends('layouts.admin.admin')

@section('content-title', 'Gambar')

@section('content-body')
    <div class="col-12 col-md-12 col-lg-12 no-padding-margin">
        <div class="card">
            <form action="{{ route('admin.rooms.rooms-images.store', $room) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-header">
                    <h4>Form Kamar</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}">
                        @error('image')
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
