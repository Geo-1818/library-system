@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Student Dashboard</h2>
            <p class="text-muted">Welcome back, {{ auth()->user()->name }}. Track your appointments and explore available services.</p>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-md-4">
            <div class="card shadow-sm stat-card stat-1">
                <div class="card-body">
                    <div class="stat-header">Available Services</div>
                    <div class="stat-value">{{ $availableServices }}</div>
                    <p class="text-muted mt-2">Services open for booking right now.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm stat-card stat-2">
                <div class="card-body">
                    <div class="stat-header">Total Appointments</div>
                    <div class="stat-value">{{ $appointmentCount }}</div>
                    <p class="text-muted mt-2">Appointments you have booked so far.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm stat-card stat-3">
                <div class="card-body">
                    <div class="stat-header">Active Appointments</div>
                    <div class="stat-value">{{ $upcomingAppointments }}</div>
                    <p class="text-muted mt-2">Appointments that are currently active.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5>Quick Actions</h5>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-primary">Browse Services</a>
                        <a href="{{ route('student.history') }}" class="btn btn-outline-secondary">View Appointment History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
