@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manage Appointment Records</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Service</th>
                        <th>Start Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->user->name }}</td>
                            <td>{{ $record->service->name }}</td>
                            <td>{{ $record->start_time }}</td>
                            <td>
                                <span class="badge 
                                    @if($record->status === 'borrowed') bg-warning
                                    @elseif($record->status === 'returned') bg-success
                                    @elseif($record->status === 'pending') bg-info
                                    @else bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($record->status === 'pending')
                                    <form action="{{ route('admin.appointments.approve', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.appointments.reject', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $records->links() }}
    </div>
</div>
@endsection
