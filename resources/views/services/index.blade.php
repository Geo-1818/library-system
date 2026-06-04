@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Available Services</h2>
            <p class="text-muted">Browse available appointment services and reserve your preferred slot.</p>
        </div>
    </div>

    <div class="row gy-4">
        @forelse ($services as $service)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text text-muted">Provider: {{ $service->provider ?? 'General' }}</p>
                        @if ($service->description)
                            <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                        @endif
                        <p class="mb-2">
                            <strong>Duration:</strong> {{ $service->duration_minutes }} min<br>
                            <strong>Slots:</strong> {{ $service->available_slots }} available
                        </p>

                        <div class="mt-auto d-flex gap-2">
                            @auth
                                @if ($service->available_slots > 0)
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-primary w-100">Book Now</a>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>Fully booked</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to Book</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No services are available right now.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
