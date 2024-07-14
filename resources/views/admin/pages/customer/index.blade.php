@extends('layouts.admin.admin')

@section('content-title', 'Customer')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 no-padding-margin">
        <div class="card">
            <div class="card-header">
                <h4>Customer</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Nama</th>
                            <th class="col-1">Email</th>
                            <th class="col-1">Telp</th>
                            <th class="col-1">Alamat</th>
                            <th class="col-1">Tanggal Bergabung</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$users" :loop="$loop"></x-iterate>
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-light">{{ $user->userable->phone_formatted }}</span></td>
                                <td class="py-3">{{ $user->userable->address }}</td>
                                <td><span class="badge badge-success">{{ $user->created_at->format('d F Y') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">Data Empty</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content-modal')
    <x-modal.delete name="Customer"></x-modal.delete>
@endsection
