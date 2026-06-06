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

        Appointment::markDurationExceededForUser($user->id);

        // Appointment stats
        $availableServices = Service::where('available_slots', '>', 0)->count();
        $appointmentCount = $user->appointments()->count();
        $appointments = $user->appointments()->with('service')->whereIn('status', ['pending', 'confirmed'])->get();

        $upcomingAppointments = $appointments->filter(function ($appointment) {
            return ! $appointment->is_duration_exceeded;
        })->count();

        $expiredAppointments = $appointments->filter(function ($appointment) {
            return $appointment->status === 'confirmed' && $appointment->is_duration_exceeded;
        })->count();

        // Library stats
        $totalBooks = Book::count();
        $borrowedBooks = $user->borrowRecords()->where('status', 'borrowed')->count();
        $returnedBooks = $user->borrowRecords()->where('status', 'returned')->count();

        return view('student.dashboard', compact(
            'availableServices',
            'appointmentCount',
            'upcomingAppointments',
            'expiredAppointments',
            'totalBooks',
            'borrowedBooks',
            'returnedBooks'
        ));
    }

    public function history()
    {
        $user = auth()->user();
        Appointment::markDurationExceededForUser($user->id);

        $records = $user->appointments()
            ->with(['service', 'schedule'])
            ->latest()
            ->paginate(10);

        return view('student.history', compact('records'));
    }
}
