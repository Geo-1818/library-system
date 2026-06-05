@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                    <li class="breadcrumb-item active">{{ $book->title }}</li>
                </ol>
            </nav>

            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="card-title mb-1">{{ $book->title }}</h1>
                    <h5 class="text-muted mb-4">by {{ $book->author }}</h5>

                    <div class="book-details mb-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>ISBN:</strong> {{ $book->isbn }}
                            </div>
                            <div class="col-md-6">
                                <strong>Available Copies:</strong> 
                                <span class="badge {{ $book->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->quantity }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($book->description)
                        <div class="description mb-4">
                            <h5>Description</h5>
                            <p class="text-secondary">{{ $book->description }}</p>
                        </div>
                    @endif

                    @auth
                        <div class="mt-4">
                            @if ($book->quantity > 0)
                                <form action="{{ route('library.borrow.store', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-book"></i> Borrow This Book
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-lg" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary">Login to Borrow</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Book Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong>Status:</strong><br>
                            @if($book->quantity > 0)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <strong>Total Copies:</strong><br>
                            {{ $book->quantity }}
                        </li>
                        <li>
                            <strong>Created:</strong><br>
                            {{ $book->created_at->format('M d, Y') }}
                        </li>
                    </ul>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Admin Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-outline-primary w-100 mb-2">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Delete</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
