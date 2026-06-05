@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">Search Results</h1>
            <p class="text-muted">Search for: <strong>"{{ $query }}"</strong></p>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('books.search') }}" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ $query }}" required>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if ($books->count() > 0)
        <p class="text-muted mb-4">Found {{ $books->total() }} result(s)</p>
        
        <div class="row g-4">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text text-muted mb-3">by {{ $book->author }}</p>
                            
                            <p class="card-text small mb-3">
                                <strong>ISBN:</strong> {{ $book->isbn }}<br>
                                <strong>Available:</strong> 
                                <span class="badge {{ $book->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->quantity }} copies
                                </span>
                            </p>

                            @if($book->description)
                                <p class="card-text small text-secondary flex-grow-1">{{ Str::limit($book->description, 100) }}</p>
                            @endif

                            <div class="mt-auto pt-3">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <nav class="mt-5">
            {{ $books->links() }}
        </nav>
    @else
        <div class="alert alert-info text-center py-5">
            <h5>No books found matching your search</h5>
            <p>Try searching with different keywords.</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">Browse All Books</a>
        </div>
    @endif
</div>
@endsection
