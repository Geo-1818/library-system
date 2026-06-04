<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Library System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body { background: #f8fafc; }
      .page-center { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
      .card { max-width: 720px; width: 100%; }
    </style>
  </head>
  <body>
    <div class="page-center">
      <div class="card shadow-sm border-0 p-4">
        <h1 class="h3 mb-3">Welcome to {{ config('app.name', 'Library System') }}</h1>
        <p class="text-muted mb-4">A short and clean interface for students and admins to book appointments and manage records.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
          <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">View Services</a>
          @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
          @endif
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
