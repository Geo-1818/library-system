@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">Library Books Management</h1>
            <p class="text-muted">Manage all books in the library system</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus"></i> Add Book
            </a>
            <a href="{{ route('admin.books.import') }}" class="btn btn-outline-primary">
                <i class="bi bi-upload"></i> Import
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

    @if($books->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Quantity</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <strong>{{ $book->title }}</strong>
                            </td>
                            <td>{{ $book->author }}</td>
                            <td><code>{{ $book->isbn }}</code></td>
                            <td>
                                <span class="badge {{ $book->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->quantity }}
                                </span>
                            </td>
                            <td>{{ $book->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <nav class="mt-4">
            {{ $books->links() }}
        </nav>
    @else
        <div class="alert alert-info text-center py-5">
            <h5>No books found</h5>
            <p>Start by adding or importing books to your library.</p>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary mt-3">Add First Book</a>
        </div>
    @endif
</div>
@endsection
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>
                                <span class="badge {{ $book->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->quantity }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.books.delete', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No books found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection
