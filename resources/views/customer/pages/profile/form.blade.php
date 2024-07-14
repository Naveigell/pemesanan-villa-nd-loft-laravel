@extends('layouts.customer.customer')

@section('content-body')
    <section class="section">
        <div class="container">
            <h1>Detail Profil</h1>
            @if ($message = session()->get('success'))
                <x-alert.success :message="$message" :dismissable="true"></x-alert.success>
            @endif
            <div class="row mb-5">
                <div class="col-7">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('customer.profile.store') }}"
                                  class="d-block" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <h6>Ubah profil.</h6>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', @$user ? $user->name : '') }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', @$user ? $user->email : '') }}">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">No Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', @$user ? $user->userable->phone : '') }}" id="phone" name="phone">
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="address" id="address" class="@error('address') is-invalid @enderror form-control" cols="30" rows="10" style="min-height: 200px; resize: none;">{{ old('address', @$user ? $user->userable->address : '') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    @if ($message = session()->get('success-password'))
                        <x-alert.success :message="$message"></x-alert.success>
                    @endif
                    <div class="card">
                        <form action="{{ route('customer.profile.password') }}" method="post">
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
                                <button class="btn btn-md btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
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



