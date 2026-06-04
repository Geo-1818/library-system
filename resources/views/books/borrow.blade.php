@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Confirm Borrow</h2>
            <p class="text-muted">Confirm borrowing this book.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Back to Catalog</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">{{ $book->title }}</h4>
            <p class="text-muted">by {{ $book->author }}</p>

            @if($book->description)
                <p>{{ $book->description }}</p>
            @endif

            <p>
                <strong>ISBN:</strong> {{ $book->isbn ?? '-' }}<br>
                <strong>Available:</strong> {{ $book->quantity }}
            </p>

            <div class="mt-3">
                @auth
                    @if($book->quantity > 0)
                        <form method="POST" action="{{ route('borrow.book', $book->id) }}">
                            @csrf
                            <button class="btn btn-primary">Confirm Borrow</button>
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </form>
                    @else
                        <div class="alert alert-warning">This book is currently unavailable.</div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Borrow</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
