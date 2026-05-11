<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.licence_reminder_dashboard') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #212529;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .sidebar .brand {
            padding: 10px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 30px;
        }
        
        .sidebar .brand img {
            width: 30px;
            border-radius: 5px;
        }

        .sidebar ul.nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul.nav-menu li {
            padding: 5px 15px;
        }

        .sidebar ul.nav-menu li a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 500;
        }

        .sidebar ul.nav-menu li a:hover,
        .sidebar ul.nav-menu li a.active {
            color: #fff;
            background-color: #0d6efd;
        }

        .sidebar ul.nav-menu li a i {
            width: 20px;
            text-align: center;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .topbar {
            background-color: #fff;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Custom Pagination Styling */
        .pagination {
            gap: 5px;
            margin-top: 15px;
        }
        .page-item .page-link {
            border: none;
            border-radius: 8px;
            color: #495057;
            font-weight: 500;
            padding: 8px 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s;
            background-color: #fff;
        }
        .page-item.active .page-link {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 8px rgba(13,110,253,0.3);
        }
        .page-item .page-link:hover:not(.disabled) {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }
        .page-item.disabled .page-link {
            background-color: #f8f9fa;
            box-shadow: none;
            color: #adb5bd;
            cursor: not-allowed;
        }

        /* Collapsed Sidebar State */
        .sidebar-collapsed .sidebar {
            left: -250px;
        }
        .sidebar-collapsed .main-content {
            margin-left: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
                left: 0 !important;
            }
            .main-content {
                margin-left: 0 !important;
            }
            .mobile-toggle {
                display: block !important;
            }
            .desktop-toggle {
                display: none !important;
            }
        }
        
        .mobile-toggle, .desktop-toggle {
            background: #fff;
            border: 1px solid #e0e0e0;
            font-size: 1.1rem;
            color: #333;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .mobile-toggle:hover, .desktop-toggle:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }

        @media (min-width: 769px) {
            .mobile-toggle {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .desktop-toggle {
                display: none !important;
            }
            .topbar {
                padding: 0 15px;
            }
            .sidebar {
                z-index: 1200;
            }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                z-index: 1150;
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .close-sidebar {
                display: block !important;
            }
        }

        .close-sidebar {
            display: none;
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: rgba(255,255,255,0.6);
            font-size: 1.5rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .close-sidebar:hover {
            color: #fff;
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">

<!-- Mobile Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <button class="close-sidebar" onclick="toggleSidebar()">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <a href="{{ route('dashboard') }}" class="brand">
        <img src="{{ asset('favicon.png') }}" alt="KCIC Logo" style="width: 30px; height: 30px; object-fit: contain;"> KCIC Licence
    </a>
    
    <ul class="nav-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> {{ __('messages.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('licences.index') }}" class="{{ request()->routeIs('licences.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check"></i> {{ __('messages.licence_management') }}
            </a>
        </li>
        @if(auth()->check() && auth()->user()->role === 'admin')
        <li>
            <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-circle-check"></i> {{ __('messages.email_settings') }}
            </a>
        </li>
        @endif
    </ul>
    
    <div class="mt-auto p-4 border-top border-secondary">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100 rounded-pill shadow-sm">
                <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>

        <button class="desktop-toggle" onclick="toggleDesktopSidebar()">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-md-block">
                <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                <div class="badge {{ auth()->user()->role === 'admin' ? 'bg-primary' : 'bg-secondary' }} small">
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>

            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle text-dark border" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-globe"></i> {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">{{ __('messages.english') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">{{ __('messages.indonesian') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        @yield('content')
    </div>
</div>

<script>
    // Initialize sidebar state from localStorage
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        document.body.classList.add('sidebar-collapsed');
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        
        sidebar.classList.toggle('show');
        if (backdrop) {
            backdrop.classList.toggle('show');
        }
        
        // Prevent scrolling when sidebar is open on mobile
        if (window.innerWidth <= 768) {
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }
    }

    function toggleDesktopSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
        // Save state to localStorage
        localStorage.setItem('sidebar-collapsed', document.body.classList.contains('sidebar-collapsed'));
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
