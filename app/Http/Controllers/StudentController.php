<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\BorrowRecord;
use App\Models\Book;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Appointment stats
        $availableServices = Service::where('available_slots', '>', 0)->count();
        $appointmentCount = $user->appointments()->count();
        $upcomingAppointments = $user->appointments()->whereIn('status', ['pending', 'confirmed'])->count();

        // Library stats
        $totalBooks = Book::count();
        $borrowedBooks = $user->borrowRecords()->where('status', 'borrowed')->count();
        $returnedBooks = $user->borrowRecords()->where('status', 'returned')->count();

        return view('student.dashboard', compact(
            'availableServices',
            'appointmentCount',
            'upcomingAppointments',
            'totalBooks',
            'borrowedBooks',
            'returnedBooks'
        ));
    }

    public function history()
    {
        $records = auth()->user()
            ->appointments()
            ->with(['service', 'schedule'])
            ->latest()
            ->paginate(10);

        return view('student.history', compact('records'));
    }
}
