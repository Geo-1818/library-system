@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">Manage Services</div>
                <div class="card-body">
                    <a href="{{ route('admin.services.import') }}" class="btn btn-success mb-3">Import Services</a>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Provider</th>
                                <th>Duration</th>
                                <th>Available Slots</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->provider }}</td>
                                    <td>{{ $service->duration_minutes }} min</td>
                                    <td>{{ $service->available_slots }}</td>
                                    <td>{{ $service->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete service?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
