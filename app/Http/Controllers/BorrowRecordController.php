<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BorrowRecordController extends Controller
{
    /**
     * Display a listing of all borrow records (Admin only).
     */
    public function index()
    {
        $records = BorrowRecord::with(['user', 'book'])
            ->latest()
            ->paginate(15);

        return view('admin.borrow-records', compact('records'));
    }

    /**
     * Display user's borrow records.
     */
    public function userBorrows()
    {
        $records = Auth::user()
            ->borrowRecords()
            ->with('book')
            ->latest()
            ->paginate(10);

        return view('student.borrow-history', compact('records'));
    }

    /**
     * Show a specific borrow record.
     */
    public function show($id)
    {
        $record = BorrowRecord::with(['user', 'book'])->findOrFail($id);

        // Check if user is the owner or an admin
        if (Auth::id() !== $record->user_id && Auth::user()->role !== 'admin') {
            return back()->with('error', 'You do not have permission to view this record.');
        }

        return view('borrow-records.show', compact('record'));
    }

    /**
     * Show borrow confirmation page for a book.
     */
    public function showBorrow($id)
    {
        $book = Book::findOrFail($id);

        return view('books.borrow', compact('book'));
    }

    /**
     * Create a new borrow record.
     */
    public function borrow(Request $request, $id)
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

        return redirect()->route('student.dashboard')->with('success', 'Book borrowed successfully!');
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook($id)
    {
        $record = BorrowRecord::findOrFail($id);

        if ($record->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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

    /**
     * Approve a borrow request (Admin only).
     */
    public function approveBorrow($id)
    {
        $record = BorrowRecord::findOrFail($id);

        $record->update(['status' => 'approved']);

        return back()->with('success', 'Borrow record approved!');
    }

    /**
     * Reject a borrow request (Admin only).
     */
    public function rejectBorrow($id)
    {
        $record = BorrowRecord::findOrFail($id);

        if ($record->status !== 'borrowed') {
            return back()->with('error', 'Can only reject borrowed records.');
        }

        $record->update(['status' => 'rejected']);
        $record->book->increment('quantity');

        return back()->with('success', 'Borrow record rejected!');
    }
}