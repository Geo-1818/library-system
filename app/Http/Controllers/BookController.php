<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display all books.
     */
    public function index()
    {
        $books = Book::paginate(12);
        return view('books.index', compact('books'));
    }

    /**
     * Show a specific book.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }

    /**
     * Search books.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $books = Book::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->paginate(12);

        return view('books.search', compact('books', 'query'));
    }

    /**
     * Show create book form (Admin).
     */
    public function create()
    {
        return view('admin.create-book');
    }

    /**
     * Store a new book (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Book::create($validated);

        return redirect()->route('admin.books')->with('success', 'Book created successfully!');
    }

    /**
     * Show edit book form (Admin).
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.edit-book', compact('book'));
    }

    /**
     * Update a book (Admin).
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $id,
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return redirect()->route('admin.books')->with('success', 'Book updated successfully!');
    }

    /**
     * Delete a book (Admin).
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books')->with('success', 'Book deleted successfully!');
    }

    /**
     * Show import books form (Admin).
     */
    public function showImportForm()
    {
        return view('admin.import-books');
    }

    /**
     * Import books from file (Admin).
     */
    public function importBooks(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:txt,csv|max:10240',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getPathname());
        $lines = preg_split('/\r?\n/', $content);

        $created = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = array_map('trim', explode('|', $line));

            if (count($parts) < 4) continue;

            Book::create([
                'title' => $parts[0] ?? 'Unknown',
                'author' => $parts[1] ?? 'Unknown',
                'isbn' => $parts[2] ?? uniqid(),
                'quantity' => isset($parts[3]) ? (int)$parts[3] : 1,
                'description' => $parts[4] ?? null,
            ]);

            $created++;
        }

        return redirect()->route('admin.books')->with('success', "Successfully imported {$created} books!");
    }
}