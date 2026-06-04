<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Appointment::with(['user', 'service', 'schedule'])->latest();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'appointment_date' => 'required_without:schedule_id|nullable|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        if ($service->available_slots <= 0) {
            return response()->json(['success' => false, 'message' => 'This service is not currently available.'], 422);
        }

        if ($user->appointments()->where('service_id', $service->id)->where('status', 'pending')->exists()) {
            return response()->json(['success' => false, 'message' => 'You already have a pending appointment for this service.'], 422);
        }

        $scheduleId = null;
        $appointmentDate = $validated['appointment_date'];

        if (! empty($validated['schedule_id'])) {
            $schedule = Schedule::where('id', $validated['schedule_id'])
                ->where('service_id', $service->id)
                ->where('available_slots', '>', 0)
                ->first();

            if (! $schedule) {
                return response()->json(['success' => false, 'message' => 'Selected schedule slot is not available.'], 422);
            }

            $appointmentDate = $schedule->available_date->format('Y-m-d') . ' ' . $schedule->start_time;
            $scheduleId = $schedule->id;
            $schedule->decrement('available_slots');
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'schedule_id' => $scheduleId,
            'appointment_date' => $appointmentDate,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        $service->decrement('available_slots');

        return response()->json([
            'success' => true,
            'message' => 'Appointment request submitted successfully.',
            'data' => $appointment->load(['service', 'schedule']),
        ], 201);
    }
}
