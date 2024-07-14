@extends('layouts.admin.admin')

@section('content-title', 'Saran')

@section('content-body')
    @if ($message = session()->get('success'))
        <x-alert.success :message="$message"></x-alert.success>
    @endif
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 no-padding-margin">
        <div class="card">
            <div class="card-header">
                <h4>Saran</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-3">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-1">Dari</th>
                            <th class="col-1">Tanggal Dibuat</th>
                            <th class="col-2">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($suggestions as $suggestion)
                            <tr>
                                <td>
                                    <x-iterate :pagination="$suggestions" :loop="$loop"></x-iterate>
                                </td>
                                <td class="py-3">{{ $suggestion->user->name }}</td>
                                <td class="py-3">{{ $suggestion->created_at->format('H:i:s, d F Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.suggestions.edit', $suggestion) }}" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">Data Empty</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $suggestions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
