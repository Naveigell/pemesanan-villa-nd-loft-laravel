@extends('layouts.admin.admin')

@section('content-title', 'Update Biodata')

@section('content-body')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="collapse show" id="mycard-collapse" style="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                @if ($message = session()->get('success'))
                                    <x-alert.success :message="$message"></x-alert.success>
                                @endif
                                <div class="card">
                                    <form action="{{ route('admin.profile.store') }}" method="post">
                                        @csrf
                                        <div class="card-header">
                                            <h4>Biodata</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Nama</label>
                                                <input type="text" id="name" value="{{ old('name', auth()->user()->name) }}" name="name" class="form-control @error('name') is-invalid @enderror">
                                                @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" id="email" value="{{ old('email', auth()->user()->email) }}" name="email" class="form-control @error('email') is-invalid @enderror">
                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-md btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-5">
                                @if ($message = session()->get('success-password'))
                                    <x-alert.success :message="$message"></x-alert.success>
                                @endif
                                <div class="card">
                                    <form action="{{ route('admin.profile.password') }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card-header">
                                            <h4>Password</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="old-password">Password Lama</label>
                                                <input type="password" id="old-password" name="old_password" class="form-control @error('old_password') is-invalid @enderror">
                                                @error('old_password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="new-password">Password Baru</label>
                                                <input type="password" id="new-password" name="password" class="form-control @error('password') is-invalid @enderror">
                                                @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="repeat-new-password">Ulangi Password Baru</label>
                                                <input type="password" id="repeat-new-password" name="retype_password" class="form-control @error('retype_password') is-invalid @enderror">
                                                @error('retype_password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-md btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
