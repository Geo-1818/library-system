@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">Appointment Records</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Service</th>
                                <th>Scheduled Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td>{{ $record->user->name }}</td>
                                    <td>{{ $record->service->name }}</td>
                                    <td>
                                        @if ($record->schedule)
                                            {{ $record->schedule->available_date->format('M d, Y') }}
                                            {{ \Carbon\Carbon::parse($record->schedule->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($record->schedule->end_time)->format('H:i') }}
                                        @else
                                            {{ optional($record->appointment_date)->format('M d, Y H:i') }}
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($record->status) }}</td>
                                    <td>
                                        @if ($record->status === 'pending')
                                            <form action="{{ route('admin.appointments.approve', $record->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.appointments.reject', $record->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        @else
                                            <span class="text-muted">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
