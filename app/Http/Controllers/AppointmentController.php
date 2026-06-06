<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function book(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'schedule_id' => 'nullable|exists:schedules,id',
            'appointment_date' => 'required_without:schedule_id|nullable|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($service->available_slots <= 0) {
            return back()->with('error', 'This service is no longer available for booking.');
        }

        if ($user->appointments()->where('service_id', $service->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending appointment for this service.');
        }

        $scheduleId = null;
        $appointmentDate = $validated['appointment_date'];

        if (! empty($validated['schedule_id'])) {
            $schedule = Schedule::where('id', $validated['schedule_id'])
                ->where('service_id', $service->id)
                ->where('available_slots', '>', 0)
                ->first();

            if (! $schedule) {
                return back()->with('error', 'Selected schedule slot is no longer available.');
            }

            $appointmentDate = $schedule->available_date->format('Y-m-d') . ' ' . $schedule->start_time;
            $scheduleId = $schedule->id;
            $schedule->decrement('available_slots');
        }

        Appointment::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'schedule_id' => $scheduleId,
            'appointment_date' => $appointmentDate,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        $service->decrement('available_slots');

        return back()->with('success', 'Appointment request submitted. An administrator will review it.');
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.book', compact('service'));
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be canceled.');
        }

        $appointment->update(['status' => 'canceled']);
        $appointment->service->increment('available_slots');

        return back()->with('success', 'Appointment canceled successfully.');
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $user = Auth::user();
        if ($user->role !== 'admin' && $appointment->user_id !== $user->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed appointments can be marked as completed.');
        }

        // Allow admins to mark completed anytime; students only after scheduled start
        if ($user->role !== 'admin') {
            if (! $appointment->appointment_date || now()->lt($appointment->appointment_date)) {
                return back()->with('error', 'You can only confirm the session after the scheduled start time.');
            }
        }

        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Appointment session marked as ended.');
    }
}
