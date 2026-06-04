<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'totalUsers' => User::count(),
                'totalBooks' => Book::count(),
                'totalBorrows' => BorrowRecord::count(),
                'activeBorrows' => BorrowRecord::where('status', 'borrowed')->count(),
                'pendingApprovals' => BorrowRecord::where('status', 'pending')->count(),
            ]
        ]);
    }

    
    public function users()
    {
        $users = User::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:student,admin',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
