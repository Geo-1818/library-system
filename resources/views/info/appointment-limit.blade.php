@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="mb-3">Appointment Duration Alert Scenario</h1>
                    <p class="text-muted">This page explains the user notification behavior when a consultation or appointment reaches its allowed duration limit.</p>

                    <div class="alert alert-info">
                        <strong>Scenario:</strong> When a user's consultation or appointment reaches the end of its scheduled duration, the system will generate a notification informing them that they have now reached the duration limit.
                    </div>

                    <h4>What happens</h4>
                    <ul>
                        <li>The system checks each booked appointment against the service duration.</li>
                        <li>When the scheduled end time is reached, the appointment is flagged as in the duration limit.</li>
                        <li>The user receives a notification stating: <strong>“You are now at the limit of your appointment duration.”</strong></li>
                        <li>The user can then act accordingly, for example by closing the session or requesting an extension if supported.</li>
                    </ul>

                    <h4>Why this matters</h4>
                    <p class="text-muted">This notification helps users stay aware of their appointment time and prevents consultations from overrunning. It also makes the booking experience more transparent and fair for all users.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Generated API Keys for Current Accounts</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>API Key</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td><code>{{ $user->api_key }}</code></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No user accounts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-muted small">
                <p>This page uses the current database connection, including SQLite if your application is configured to use SQLite. It displays the automatically generated API key for every user account in the system.</p>
            </div>
        </div>
    </div>
</div>
@endsection
