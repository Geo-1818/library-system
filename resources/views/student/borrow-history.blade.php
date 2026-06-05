@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">My Borrow History</h1>
            <p class="text-muted">Track all your borrowed books</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Borrow Books
            </a>
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
                        <th>Book Title</th>
                        <th>Author</th>
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
                                <strong>{{ $record->book->title }}</strong>
                            </td>
                            <td>{{ $record->book->author }}</td>
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
                                    @else bg-warning 
                                    @endif">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td>
                                @if($record->status === 'borrowed')
                                    <form action="{{ route('library.return', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Return this book?')">
                                            Return
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
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
            <p>Start borrowing books from our library!</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">Browse Books</a>
        </div>
    @endif
</div>
@endsection
