@extends('layouts.admin.admin')

@section('content-title', 'Fasilitas')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 no-padding-margin">
        <div class="card">
            <div class="card-header">
                <h4>Kamar</h4>
                @if(auth()->user()->isAdmin())
                    <div class="card-header-action">
                        <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Fasilitas</a>
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Nama Fasilitas</th>
                            <th class="col-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($facilities as $facility)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$facilities" :loop="$loop"></x-iterate>
                                </td>
                                <td class="py-3">{{ $facility->name }}</td>
                                <td>
                                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                                    <button class="btn btn-danger btn-action trigger--modal-delete cursor-pointer" data-url="{{ route('admin.facilities.destroy', $facility) }}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center;">Data Empty</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $facilities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content-modal')
    <x-modal.delete name="Fasilitas"></x-modal.delete>
@endsection
