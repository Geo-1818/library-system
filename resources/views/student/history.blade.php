@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Your Appointment History</h2>
            <p class="text-muted">Review your past and upcoming appointment records.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($records->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td>
                                        <strong>{{ $record->service->name }}</strong><br>
                                        <small class="text-muted">{{ $record->service->provider }}</small>
                                    </td>
                                    <td>{{ $record->appointment_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge {{ $record->status === 'pending' ? 'bg-info text-dark' : ($record->status === 'confirmed' ? 'bg-success' : 'bg-danger') }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-muted">No action</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $records->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    You have no appointment history yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
