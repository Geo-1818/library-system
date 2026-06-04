@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">Edit Service</div>
                <div class="card-body">
                    <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="provider" class="form-label">Provider</label>
                            <input type="text" name="provider" id="provider" class="form-control" value="{{ old('provider', $service->provider) }}">
                        </div>

                        <div class="mb-3">
                            <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', $service->duration_minutes) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="available_slots" class="form-label">Available Slots</label>
                            <input type="number" name="available_slots" id="available_slots" class="form-control" value="{{ old('available_slots', $service->available_slots) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <button class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('admin.services') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
