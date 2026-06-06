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

    <header class="container hero d-flex align-items-center">
      <div class="row w-100">
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h1 class="display-5 mb-3">Welcome to the Online Booking System</h1>
          <p class="lead text-muted mb-4">Browse books, borrow, and manage your records. Simple, clean, and built for students and admins.</p>
          <p>
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg me-2">Borrow Books</a>
            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Sign in</a>
            @endif
          </p>
        </div>
        <div class="col-lg-6 d-none d-lg-block">
          <div class="card border-0 shadow-sm">
            <img src="/img/library-hero.jpg" alt="Library" class="card-img-top" style="object-fit:cover; height:360px;">
          </div>
        </div>
      </div>
    </header>

    <section class="container py-5">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Student Dashboard</h5>
              <p class="card-text text-muted">View borrowed books, history, and quick actions.</p>
              <a href="{{ url('/dashboard') }}" class="stretched-link text-decoration-none"></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Browse Books</h5>
              <p class="card-text text-muted">Search and find books available for borrowing.</p>
              <a href="{{ route('books.index') }}" class="stretched-link text-decoration-none"></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Admin Tools</h5>
              <p class="card-text text-muted">Import services, manage users and appointments (admin only).</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="bg-white text-muted py-4 mt-auto">
      <div class="container d-flex justify-content-between">
        <small>&copy; {{ date('Y') }} {{ config('app.name', 'Library System') }}.</small>
        <small>Built for your online booking needs</small>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
