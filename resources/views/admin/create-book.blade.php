@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.books') }}">Books</a></li>
                    <li class="breadcrumb-item active">Add New Book</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Book</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.books.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Book Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author *</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                   id="author" name="author" value="{{ old('author') }}" required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN *</label>
                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                   id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Book
                            </button>
                            <a href="{{ route('admin.books') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
