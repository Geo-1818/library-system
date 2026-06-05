@extends('layouts.app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.borrow-records') }}">Borrow Records</a></li>
            <li class="breadcrumb-item active">Record #{{ $record->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Borrow Record Details</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>User Information</h5>
                            <p>
                                <strong>Name:</strong> {{ $record->user->name }}<br>
                                <strong>Email:</strong> <a href="mailto:{{ $record->user->email }}">{{ $record->user->email }}</a><br>
                                <strong>Role:</strong> <span class="badge bg-primary">{{ ucfirst($record->user->role) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Book Information</h5>
                            <p>
                                <strong>Title:</strong> {{ $record->book->title }}<br>
                                <strong>Author:</strong> {{ $record->book->author }}<br>
                                <strong>ISBN:</strong> <code>{{ $record->book->isbn }}</code>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Borrow Details</h5>
                            <p>
                                <strong>Borrow Date:</strong> {{ $record->borrow_date->format('M d, Y \a\t g:i A') }}<br>
                                <strong>Return Date:</strong> 
                                @if($record->return_date)
                                    {{ $record->return_date->format('M d, Y \a\t g:i A') }}
                                @else
                                    <span class="text-muted">Not yet returned</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Status</h5>
                            <p>
                                <strong>Current Status:</strong><br>
                                <span class="badge 
                                    @if($record->status === 'returned') bg-success 
                                    @elseif($record->status === 'borrowed') bg-info 
                                    @elseif($record->status === 'approved') bg-primary
                                    @elseif($record->status === 'rejected') bg-danger
                                    @else bg-warning 
                                    @endif" style="font-size: 1rem;">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    @if($record->status === 'borrowed')
                        <form action="{{ route('admin.borrow-records.approve', $record->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this borrow?')">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.borrow-records.reject', $record->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this borrow?')">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <small>No actions available for this status.</small>
                        </div>
                    @endif

                    <hr>

                    <a href="{{ route('admin.borrow-records') }}" class="btn btn-outline-secondary w-100">Back to Records</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
