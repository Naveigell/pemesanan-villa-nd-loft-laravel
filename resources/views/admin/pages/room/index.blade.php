@extends('layouts.admin.admin')

@section('content-title', 'Kamar')

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
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kamar</a>
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Nama Kamar</th>
                            <th class="col-1">Kode Kamar</th>
                            <th class="col-1">Fasilitas</th>
                            <th class="col-1">Harga</th>
                            <th class="col-1">Warna</th>
                            <th class="col-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$rooms" :loop="$loop"></x-iterate>
                                </td>
                                <td class="py-3">{{ $room->name }}</td>
                                <td>{{ $room->code }}</td>
                                <td>
                                    <ul class="p-0">
                                        @forelse ($room->facilities as $facility)
                                            <li style="list-style-type: none;"><span class="badge badge-success">{{ $facility->name }}</span></li>
                                        @empty
                                            -
                                        @endforelse
                                    </ul>
                                </td>
                                <td>{{ format_price($room->price) }}</td>
                                <td>
                                    <span class="d-inline-block" style="background-color: {{ $room->color }}; width: 20px; height: 20px;"></span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                                    <button class="btn btn-danger btn-action trigger--modal-delete cursor-pointer" data-url="{{ route('admin.rooms.destroy', $room) }}"><i class="fas fa-trash"></i></button>
                                </td>
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
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content-modal')
    <x-modal.delete :name="'Kamar'"></x-modal.delete>
@endsection
