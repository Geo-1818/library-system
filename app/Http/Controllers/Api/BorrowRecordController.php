<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowRecordController extends Controller
{
    
    public function index()
    {
        $records = BorrowRecord::with('user', 'book')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    
    public function userBorrows()
    {
        $records = BorrowRecord::where('user_id', Auth::id())
            ->with('book')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    
    public function show($id)
    {
        $record = BorrowRecord::with('user', 'book')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    
    public function borrow(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Book is not available'
            ], 400);
        }

        $record = BorrowRecord::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'borrow_date' => now(),
            'status' => 'pending'
        ]);

        $book->decrement('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Borrow request submitted',
            'data' => $record
        ], 201);
    }

    
    public function returnBook($id)
    {
        $record = BorrowRecord::findOrFail($id);

        if ($record->status === 'returned') {
            return response()->json([
                'success' => false,
                'message' => 'This book has already been returned'
            ], 400);
        }

        $record->update([
            'status' => 'returned',
            'return_date' => now()
        ]);

        $record->book->increment('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Book returned successfully',
            'data' => $record
        ]);
    }

    
    public function approveBorrow($id)
    {
        $record = BorrowRecord::findOrFail($id);
        $record->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Borrow approved',
            'data' => $record
        ]);
    }

    
    public function rejectBorrow($id)
    {
        $record = BorrowRecord::findOrFail($id);
        $record->book->increment('quantity');
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrow rejected'
        ]);
    }
}
