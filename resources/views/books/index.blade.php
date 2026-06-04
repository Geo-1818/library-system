@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Available Books</h2>
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

    <div class="row">
        @forelse ($books as $book)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body d-flex">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text text-muted">by {{ $book->author }}</p>
                        <p class="card-text">
                            <small>ISBN: {{ $book->isbn }}</small>
                        </p>
                        @if ($book->description)
                            <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                        @endif
                        <div class="book-actions mt-auto d-flex">
                            <span class="badge {{ $book->quantity > 0 ? 'bg-success' : 'bg-danger' }} mb-2">
                                {{ $book->quantity > 0 ? $book->quantity . ' available' : 'Out of stock' }}
                            </span>

                            @auth
                                @if ($book->quantity > 0)
                                    <div class="d-flex gap-2 w-100">
                                        <form action="{{ route('borrow.book', $book->id) }}" method="POST" class="flex-fill">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">Borrow</button>
                                        </form>
                                        <a href="{{ route('books.borrow', $book->id) }}" class="btn btn-outline-primary flex-fill">Borrow Page</a>
                                    </div>
                                @else
                                    <button class="btn btn-secondary" disabled>Not Available</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login to Borrow</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No books available at the moment.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection