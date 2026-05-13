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
        :root {
            --kcic-red: #BE1E2D;
            --kcic-red-hover: #9b1824;
        }

        /* Override Bootstrap Primary */
        .btn-primary {
            background-color: var(--kcic-red) !important;
            border-color: var(--kcic-red) !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--kcic-red-hover) !important;
            border-color: var(--kcic-red-hover) !important;
        }
        .text-primary {
            color: var(--kcic-red) !important;
        }
        .bg-primary {
            background-color: var(--kcic-red) !important;
        }

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
            background-color: #ffffff;
            color: #333;
            padding-top: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
        }
        
        .sidebar .brand {
            padding: 10px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--kcic-red);
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
            color: #555;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 600;
        }

        .sidebar ul.nav-menu li a:hover,
        .sidebar ul.nav-menu li a.active {
            color: #fff !important;
            background-color: var(--kcic-red);
            box-shadow: 0 4px 10px rgba(190, 30, 45, 0.2);
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
            background-color: var(--kcic-red);
            color: white;
            box-shadow: 0 4px 8px rgba(190, 30, 45, 0.2);
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

        /* Skeleton Loader Styles */
        .skeleton-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f4f6f9;
            z-index: 50;
            padding: 30px;
            display: flex;
            flex-direction: column;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .skeleton-overlay.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .skeleton-box {
            background: #e2e5e9;
            background: linear-gradient(90deg, #e2e5e9 25%, #f0f2f5 50%, #e2e5e9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite linear;
            border-radius: 8px;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .skeleton-header { height: 40px; width: 30%; margin-bottom: 30px; }
        .skeleton-card { height: 120px; width: 100%; }
        .skeleton-row { height: 60px; width: 100%; margin-bottom: 10px; }

        /* Actual Content Wrapper */
        #actual-content {
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        #actual-content.loaded {
            opacity: 1;
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
            background-color: var(--kcic-red);
            color: #fff;
            border-color: var(--kcic-red);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(190, 30, 45, 0.2);
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
            color: #333;
            font-size: 1.5rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .close-sidebar:hover {
            color: var(--kcic-red);
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
            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle text-dark border d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                        <div class="badge {{ auth()->user()->role === 'admin' ? 'bg-primary' : 'bg-secondary' }} small">
                            {{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                    <i class="fa-solid fa-user-circle fs-4 text-secondary d-md-none"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fa-solid fa-key me-2"></i> {{ __('Change Password') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> {{ __('Logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle text-dark border" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-globe"></i> {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">{{ __('messages.english') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">{{ __('messages.indonesian') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area position-relative" id="main-content-area">
        
        <!-- Global Skeleton Loader -->
        <div class="skeleton-overlay" id="skeleton-loader">
            <div class="skeleton-box skeleton-header"></div>
            
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3"><div class="skeleton-box skeleton-card"></div></div>
                <div class="col-md-3 col-6 mb-3"><div class="skeleton-box skeleton-card"></div></div>
                <div class="col-md-3 col-6 mb-3"><div class="skeleton-box skeleton-card"></div></div>
                <div class="col-md-3 col-6 mb-3"><div class="skeleton-box skeleton-card"></div></div>
            </div>
            
            <div class="skeleton-box skeleton-row" style="height: 40px;"></div>
            <div class="skeleton-box skeleton-row"></div>
            <div class="skeleton-box skeleton-row"></div>
            <div class="skeleton-box skeleton-row"></div>
            <div class="skeleton-box skeleton-row"></div>
        </div>

        <!-- Actual Content -->
        <div id="actual-content">
            @yield('content')
        </div>

    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('password.change') }}" method="POST" id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">New Password</label>
                        <input type="password" class="form-control" name="new_password" required minlength="8">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Confirm New Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Update Password</button>
                </form>
            </div>
        </div>
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

    // Handle Skeleton Loader transition on initial load
    document.addEventListener("DOMContentLoaded", function() {
        // Small delay to ensure smooth transition and make the skeleton visible briefly
        setTimeout(() => {
            hideSkeleton();
        }, 300); // 300ms delay
    });

    function showSkeleton() {
        const skeleton = document.getElementById('skeleton-loader');
        const content = document.getElementById('actual-content');
        if (skeleton && content) {
            skeleton.style.display = 'flex';
            skeleton.classList.remove('hidden');
            content.classList.remove('loaded');
        }
    }

    function hideSkeleton() {
        const skeleton = document.getElementById('skeleton-loader');
        const content = document.getElementById('actual-content');
        if (skeleton) {
            skeleton.classList.add('hidden');
            setTimeout(() => { skeleton.style.display = 'none'; }, 400);
        }
        if (content) {
            content.classList.add('loaded');
        }
    }

    // Intercept link clicks to create an SPA-like feel
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        // Ignore external links, anchors, javascript links, or links opening in a new tab
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank') return;

        // If it's an internal link on the same domain
        if (link.hostname === window.location.hostname) {
            e.preventDefault(); // Stop immediate navigation
            
            // Show skeleton loader instantly
            showSkeleton();

            // Navigate to the new page after a tiny delay so the UI updates
            setTimeout(() => {
                window.location.href = link.href;
            }, 50);
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#BE1E2D'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `
                <ul class="text-start mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#BE1E2D'
        });
    @endif
</script>
</body>
</html>
