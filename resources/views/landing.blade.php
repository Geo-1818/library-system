<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Online Booking System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body { background: #f8fafc; }
      .hero { min-height: 72vh; }
      .brand { font-weight:700; letter-spacing: -0.02em; }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand brand" href="{{ url('/') }}">{{ config('app.name', 'Online Booking System') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
            @if (Route::has('login'))
              @auth
                <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li class="nav-item">
                  <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-link nav-link">Logout</button></form>
                </li>
              @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                @if (Route::has('register'))
                  <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endif
              @endauth
            @endif
          </ul>
        </div>
      </div>
    </nav>

    <main class="container hero d-flex align-items-center justify-content-center">
      <div class="row w-100">
        <div class="col-lg-8 mx-auto text-center">
          <h1 class="display-5 mb-3">Simple Library Booking</h1>
          <p class="lead text-muted mb-4">Browse services, reserve appointments, and manage your account quickly without extra pages.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">Browse Services</a>
            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Sign in</a>
            @endif
          </div>
        </div>
      </div>
    </main>

    <footer class="bg-white text-muted py-4 text-center">
      <div class="container">
        <small>&copy; {{ date('Y') }} {{ config('app.name', 'Library System') }}. Built for easy booking.</small>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
