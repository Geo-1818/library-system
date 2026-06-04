@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Book Appointment</h2>
            <p class="text-muted">Choose a date and submit your appointment request for this service.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Back to Services</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">{{ $service->name }}</h4>
            <p class="text-muted">Provider: {{ $service->provider ?? 'General' }}</p>

            @if ($service->description)
                <p>{{ $service->description }}</p>
            @endif

            <p>
                <strong>Duration:</strong> {{ $service->duration_minutes }} minutes<br>
                <strong>Available slots:</strong> {{ $service->available_slots }}
            </p>

            @auth
                @if ($service->available_slots > 0)
                    <form method="POST" action="{{ route('appointments.book', $service->id) }}">
                        @csrf

                        @if ($service->schedules->count())
                            <div class="mb-3">
                                <label for="schedule_id" class="form-label">Select an Available Schedule Slot</label>
                                <select class="form-select" id="schedule_id" name="schedule_id">
                                    <option value="">Choose a slot or enter your own date/time</option>
                                    @foreach ($service->schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->available_date->format('M d, Y') }}
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            ({{ $schedule->available_slots }} {{ $schedule->available_slots === 1 ? 'slot' : 'slots' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('schedule_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Preferred Appointment Date</label>
                            <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}">
                            <div class="form-text">Leave blank if you have chosen one of the available schedule slots above.</div>
                            @error('appointment_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary">Request Appointment</button>
                    </form>
                @else
                    <div class="alert alert-warning">This service is fully booked.</div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login to book</a>
            @endauth
        </div>
    </div>
</div>
@endsection
