<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;

class BorrowRecordController extends Controller
{
    public function borrow($id)
    {
        $book = Book::findOrFail($id);
        $user = Auth::user();

        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is not currently available.');
        }

        if ($user->borrowRecords()->where('book_id', $book->id)->where('status', 'borrowed')->exists()) {
            return back()->with('error', 'You already borrowed this book.');
        }

        BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'borrowed'
        ]);

        $book->decrement('quantity');

        return back()->with('success', 'Book borrowed successfully!');
    }

    /**
     * Show borrow confirmation page for a book.
     */
    public function showBorrow($id)
    {
        $book = Book::findOrFail($id);

        return view('books.borrow', compact('book'));
    }

    public function returnBook($id)
    {
        $record = BorrowRecord::findOrFail($id);

        if ($record->user_id !== Auth::id()) {
            return back()->with('error', 'You do not have permission to return this record.');
        }

        if ($record->status === 'returned') {
            return back()->with('error', 'This borrow record has already been returned.');
        }

        $record->update([
            'status' => 'returned',
            'return_date' => now()
        ]);

        $record->book->increment('quantity');

        return back()->with('success', 'Book returned successfully!');
    }
}