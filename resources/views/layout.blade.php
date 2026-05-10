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
            transition: all 0.3s;
            z-index: 1000;
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
            justify-content: flex-end;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block !important;
            }
        }
        
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #333;
            margin-right: auto;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="fa-solid fa-shield-halved text-primary"></i> KCIC Licence
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
        <li>
            <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-circle-check"></i> {{ __('messages.email_settings') }}
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        
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

    <!-- Content Area -->
    <div class="content-area">
        @yield('content')
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
