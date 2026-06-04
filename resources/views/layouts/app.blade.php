<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        main.content-with-sidebar {
            width: 100%;
            min-height: calc(100vh - 120px);
            padding: 24px;
            transition: margin-left 0.28s ease;
            box-sizing: border-box;
        }

        footer {
            width: 100%;
        }

        /* Admin sidebar styles */
        .admin-sidebar {
            position: fixed;
            left: -260px;
            top: 0;
            bottom: 0;
            width: 260px;
            background: #212529;
            color: #fff;
            padding: 12px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.2);
            transition: left 0.28s ease;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }

        .admin-sidebar.open { left: 0; }

        .admin-sidebar .sidebar-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
        .admin-sidebar .sidebar-body { margin-top:8px; display: flex; flex-direction: column; flex: 1; overflow-y: auto; padding-bottom: 16px; }
        .admin-sidebar .sidebar-footer { margin-top: auto; }

        .admin-sidebar.open + .sidebar-toggle-fixed {
            display: none;
        }

        @media(min-width: 992px) {
            .content-with-sidebar.sidebar-open { margin-left: 260px; }
        }

        @media(max-width: 991px) {
            .admin-sidebar { top: 0; height: 100%; }
        }

        .admin-user main.content-with-sidebar { min-height: 100vh; padding-top: 24px; }

        .sidebar-toggle-fixed {
            position: fixed;
            left: 8px;
            top: 80px;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #212529;
            color: #fff;
            border: none;
            z-index: 1060;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar-toggle-fixed:hover { cursor: pointer; opacity: 0.95; }
        /* Book card action styles */
        .card-body.d-flex { display: flex; flex-direction: column; }
        .book-actions { gap: .5rem; display: flex; flex-direction: column; }
        .book-actions .badge { align-self: flex-start; }
        .book-actions .btn.flex-fill { min-width: 0; }

        /* Dashboard stat card colors */
        .stat-card { border-radius: 6px; padding: 18px; }
        .stat-card .stat-header { font-weight: 600; }
        .stat-card.stat-1 { background: linear-gradient(180deg, #e9f7ef 0%, #ffffff 100%); border: 1px solid #d1efd6; }
        .stat-card.stat-2 { background: linear-gradient(180deg, #eef4ff 0%, #ffffff 100%); border: 1px solid #d6e6ff; }
        .stat-card.stat-3 { background: linear-gradient(180deg, #fff7e9 0%, #ffffff 100%); border: 1px solid #ffecd1; }
        .stat-value { font-size: 1.75rem; font-weight: 600; margin-top: 8px; }
    </style>
</head>
<body class="@auth @if(auth()->user()->role === 'admin') admin-user @endif @endauth">
    @auth
        @if(auth()->user()->role !== 'admin')
            {{-- Student navigation is provided in the sidebar --}}
        @endif
    @else
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">� Online Booking System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    {{-- Sidebar for admin actions --}}
    @auth
        @if (auth()->user()->role === 'admin')
            <div id="adminSidebar" class="admin-sidebar">
                <div class="sidebar-header">
                    <strong>Admin</strong>
                    <button class="btn btn-sm btn-light d-none d-lg-inline" id="sidebarClose">×</button>
                </div>
                <div class="sidebar-body d-flex flex-column h-100">
                    <a href="{{ route('admin.services.import') }}" class="btn btn-primary w-100 mb-3">Import Services</a>
                    <a href="{{ route('admin.services') }}" class="btn btn-outline-light w-100 mb-2">Services</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light w-100 mb-2">Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-light w-100 mb-2">Manage Users</a>
                    <a href="{{ route('admin.services') }}" class="btn btn-outline-light w-100 mb-2">Manage Services</a>
                    <a href="{{ route('admin.appointments') }}" class="btn btn-outline-light w-100 mb-2">Manage Appointments</a>
                    <div class="sidebar-footer mt-auto pt-3 border-top border-secondary">
                        <div class="mb-2 text-white-50">Signed in as</div>
                        <div class="fw-bold mb-2">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role !== 'admin')
            <div id="userSidebar" class="admin-sidebar">
                <div class="sidebar-header">
                    <strong>Student</strong>
                    <button class="btn btn-sm btn-light d-none d-lg-inline" id="sidebarClose">×</button>
                </div>
                <div class="sidebar-body d-flex flex-column h-100">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary w-100 mb-3">Dashboard</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-light w-100 mb-2">Browse Services</a>
                    <a href="{{ route('student.history') }}" class="btn btn-outline-light w-100 mb-2">My History</a>
                    <div class="sidebar-footer mt-auto pt-3 border-top border-secondary">
                        <div class="mb-2 text-white-50">Signed in as</div>
                        <div class="fw-bold mb-2">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    {{-- Fixed side toggle button (visible for authenticated users) --}}
    @auth
        <button id="sidebarToggleFixed" class="sidebar-toggle-fixed" aria-label="Toggle sidebar">☰</button>
    @endauth

    <main class="content-with-sidebar">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2026 Library Management System. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sidebar = document.querySelector('#adminSidebar, #userSidebar');
            const toggles = Array.from(document.querySelectorAll('#sidebarToggle, #sidebarToggleFixed'));
            const closeBtn = document.getElementById('sidebarClose');
            const content = document.querySelector('.content-with-sidebar');

            function openSidebar(){
                if(!sidebar) return;
                sidebar.classList.add('open');
                if(content) content.classList.add('sidebar-open');
            }

            function closeSidebar(){
                if(!sidebar) return;
                sidebar.classList.remove('open');
                if(content) content.classList.remove('sidebar-open');
            }

            function syncSidebarState(){
                if(!sidebar) return;
                if(window.innerWidth >= 992) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }

            toggles.forEach(function(toggle){
                if(!toggle) return;
                toggle.addEventListener('click', function(e){
                    e.preventDefault();
                    if(sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
                });
            });

            if(closeBtn) closeBtn.addEventListener('click', function(e){
                e.preventDefault(); closeSidebar();
            });

            // Close sidebar when clicking outside on small screens
            document.addEventListener('click', function(e){
                if(!sidebar) return;
                if(window.innerWidth <= 991 && sidebar.classList.contains('open')){
                    // if click is outside sidebar and outside any toggle, close
                    const clickedInsideToggle = toggles.some(t => t && t.contains(e.target));
                    if(!sidebar.contains(e.target) && !clickedInsideToggle){
                        closeSidebar();
                    }
                }
            });
        });
    </script>
</body>
</html>
