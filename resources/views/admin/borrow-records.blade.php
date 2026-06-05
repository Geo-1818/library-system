@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">Borrow Records Management</h1>
            <p class="text-muted">Track and manage all book borrowing records</p>
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

    @if($records->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->user->name }}</strong><br>
                                <small class="text-muted">{{ $record->user->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $record->book->title }}</strong><br>
                                <small class="text-muted">by {{ $record->book->author }}</small>
                            </td>
                            <td>{{ $record->borrow_date->format('M d, Y') }}</td>
                            <td>
                                @if($record->return_date)
                                    {{ $record->return_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($record->status === 'returned') bg-success 
                                    @elseif($record->status === 'borrowed') bg-info 
                                    @elseif($record->status === 'approved') bg-primary
                                    @elseif($record->status === 'rejected') bg-danger
                                    @else bg-warning 
                                    @endif">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.borrow-records.show', $record->id) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($record->status === 'borrowed')
                                        <form action="{{ route('admin.borrow-records.approve', $record->id) }}" method="POST" class="d-inline" title="Approve">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Approve this borrow?')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.borrow-records.reject', $record->id) }}" method="POST" class="d-inline" title="Reject">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Reject this borrow?')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <nav class="mt-4">
            {{ $records->links() }}
        </nav>
    @else
        <div class="alert alert-info text-center py-5">
            <h5>No borrow records yet</h5>
            <p>Borrow records will appear here once users start borrowing books.</p>
        </div>
    @endif
</div>
@endsection
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
