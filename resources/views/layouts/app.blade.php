<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pengaduan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --brand-600: #1f4bff;
            --brand-500: #3b61ff;
            --brand-100: #e8efff;
            --ink-900: #0b1220;
            --ink-600: #51607a;
            --ink-400: #8a97ad;
            --line-200: #eef1f7;
            --sidebar-width: 260px;
            --sidebar-bg: #ffffff;
        }

        body {
            font-family: "Sora", "Segoe UI", system-ui, sans-serif;
            background: radial-gradient(900px 600px at 80% -10%, #eef2ff 0%, #f7f8fc 45%, #f8f9fb 100%);
            color: var(--ink-900);
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--line-200);
            padding: 28px 22px;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 22px;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-600), #6a8dff);
            box-shadow: 0 10px 24px rgba(31, 75, 255, 0.35);
            font-size: 20px;
        }

        .brand-title {
            font-weight: 700;
            letter-spacing: 0.2px;
            color: var(--ink-900);
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--ink-400);
            margin-top: 2px;
        }

        .menu-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.4px;
            color: var(--ink-400);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .sidebar-link,
        .sidebar-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            color: var(--ink-600);
            text-decoration: none;
            border: 1px solid transparent;
            background: transparent;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            width: 100%;
            text-align: left;
        }

        .sidebar-link:hover,
        .sidebar-link:focus,
        .sidebar-button:hover,
        .sidebar-button:focus {
            background: var(--brand-100);
            color: var(--brand-600);
        }

        .sidebar-link.active {
            background: var(--brand-600);
            color: #fff;
            box-shadow: 0 10px 18px rgba(31, 75, 255, 0.25);
        }

        .sidebar-link.active .sidebar-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .sidebar-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f4ff;
            color: var(--brand-600);
            font-size: 16px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid var(--line-200);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f2f5fb;
            color: var(--brand-600);
            font-weight: 700;
        }

        .sidebar-logout {
            justify-content: flex-start;
        }

        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .content-inner {
            padding: 32px 32px 48px;
        }

        .topbar {
            display: none;
            padding: 16px 20px;
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--line-200);
        }

        .landing-topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 16px;
        }

        .landing-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 18px;
            font-weight: 600;
            border: 1px solid rgba(31, 75, 255, 0.25);
            background: #ffffff;
            color: var(--brand-600);
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(31, 75, 255, 0.16);
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        .landing-login-btn:hover {
            background: var(--brand-600);
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(31, 75, 255, 0.22);
        }

        .landing-login-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(31, 75, 255, 0.2);
        }

        /* ========================================
           RESPONSIVE DESIGN - MOBILE FIRST
           ======================================== */

        /* Tablet & Below (max-width: 991.98px) */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }
            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }
            .content-inner {
                padding: 24px 16px 32px;
            }
            
            /* Make tables responsive */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Stack cards vertically */
            .row.g-3 > [class*="col-"],
            .row.g-4 > [class*="col-"] {
                margin-bottom: 1rem;
            }
        }

        /* Mobile (max-width: 767.98px) */
        @media (max-width: 767.98px) {
            .content-inner {
                padding: 20px 12px 28px;
            }
            
            /* Responsive typography */
            h1, .h1 { font-size: 1.75rem !important; }
            h2, .h2 { font-size: 1.5rem !important; }
            h3, .h3 { font-size: 1.25rem !important; }
            h4, .h4 { font-size: 1.1rem !important; }
            h5, .h5 { font-size: 1rem !important; }
            
            /* Responsive buttons */
            .btn {
                padding: 10px 16px;
                font-size: 14px;
            }
            
            .btn-lg {
                padding: 12px 20px;
                font-size: 16px;
            }
            
            .btn-sm {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            /* Touch-friendly form controls */
            .form-control,
            .form-select {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 12px 14px;
            }
            
            /* Responsive modals */
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 16px;
            }
            
            /* Stack action buttons */
            .d-flex.gap-2 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            .d-flex.gap-2 .btn {
                width: 100% !important;
            }
            
            /* Responsive cards */
            .card {
                border-radius: 12px;
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Hide text on small screens, show icons only */
            .btn .d-none.d-sm-inline {
                display: none !important;
            }
        }

        /* Small Mobile (max-width: 575.98px) */
        @media (max-width: 575.98px) {
            .content-inner {
                padding: 16px 10px 24px;
            }
            
            /* Full width buttons */
            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            /* Responsive tables */
            .table {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
            }
            
            /* Hide less important columns on mobile */
            .table .d-none.d-md-table-cell {
                display: none !important;
            }
            
            /* Stack form rows */
            .row.g-3 {
                gap: 0.75rem !important;
            }
        }

        /* Tablet Portrait (768px - 991px) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .content-inner {
                padding: 28px 20px 36px;
            }
            
            /* 2 columns on tablet */
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Desktop (min-width: 992px) */
        @media (min-width: 992px) {
            /* Ensure sidebar is visible */
            .sidebar {
                display: flex !important;
            }
            
            .topbar {
                display: none !important;
            }
        }

        /* Large Desktop (min-width: 1200px) */
        @media (min-width: 1200px) {
            .content-inner {
                padding: 40px 48px 60px;
                max-width: 1400px;
            }
        }

        /* Extra Large Desktop (min-width: 1400px) */
        @media (min-width: 1400px) {
            .content-inner {
                padding: 48px 64px 72px;
                max-width: 1600px;
            }
        }

        /* ========================================
           RESPONSIVE UTILITIES
           ======================================== */
        
        /* Responsive spacing */
        @media (max-width: 767.98px) {
            .mb-4, .my-4 { margin-bottom: 1.5rem !important; }
            .mt-4, .my-4 { margin-top: 1.5rem !important; }
            .mb-5, .my-5 { margin-bottom: 2rem !important; }
            .mt-5, .my-5 { margin-top: 2rem !important; }
        }
        
        /* Responsive text alignment */
        @media (max-width: 767.98px) {
            .text-md-start { text-align: center !important; }
            .text-md-end { text-align: center !important; }
        }
        
        /* Touch-friendly clickable areas */
        @media (max-width: 991.98px) {
            a, button, .btn {
                min-height: 44px; /* iOS recommended touch target */
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Offcanvas responsive styling */
        .offcanvas {
            max-width: 280px;
        }

        .offcanvas-body {
            padding: 1.5rem 1rem;
        }
    </style>
</head>
<body>
    @php
        $isAuthPage = request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*');
        $showSidebar = auth()->check() && !request()->routeIs('home') && !$isAuthPage;
    @endphp
    <div class="app-shell">
        @if ($showSidebar)
            <aside class="sidebar">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="brand">
                    <div class="brand-icon"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="brand-title">DISDUKCAPIL</div>
                        <div class="brand-subtitle">Sistem Pengaduan</div>
                    </div>
                </div>
            </a>

            <div class="menu-label">MAIN MENU</div>
            <ul class="sidebar-nav">
                @auth
                    <li>
                        <a class="sidebar-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}" href="{{ route('pengaduan.index') }}">
                            <span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span>
                            Pengaduan
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                                <span class="sidebar-icon"><i class="bi bi-tags"></i></span>
                                Kategori
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                                User
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                                <span class="sidebar-icon"><i class="bi bi-bar-chart"></i></span>
                                Laporan
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                                <span class="sidebar-icon"><i class="bi bi-chat-square-dots"></i></span>
                                Feedback
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.create') }}">
                                <span class="sidebar-icon"><i class="bi bi-chat-square-dots"></i></span>
                                Feedback
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="sidebar-footer">
                @guest
                    <button class="sidebar-button" data-bs-toggle="modal" data-bs-target="#modalLoginAdmin">
                        <span class="sidebar-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                        Login Admin
                    </button>
                @else
                    <div class="user-info">
                        <div class="user-badge">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="sidebar-button sidebar-logout" type="submit">
                            <span class="sidebar-icon"><i class="bi bi-box-arrow-right"></i></span>
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
            </aside>
        @endif

        <div class="content-area">
            @if ($showSidebar)
                <div class="topbar d-lg-none">
                    <button class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="fw-semibold">DISDUKCAPIL</div>
                </div>
            @endif

            <div class="content-inner">
                @if (request()->routeIs('home'))
                    <div class="landing-topbar">
                        @guest
                            <a class="landing-login-btn" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <a class="landing-login-btn" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </a>
                            </form>
                        @endguest
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    @if ($showSidebar)
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
            <ul class="sidebar-nav">
                @auth
                    <li>
                        <a class="sidebar-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}" href="{{ route('pengaduan.index') }}">
                            <span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span>
                                Pengaduan
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                                    <span class="sidebar-icon"><i class="bi bi-tags"></i></span>
                                    Kategori
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                                    User
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                                    <span class="sidebar-icon"><i class="bi bi-bar-chart"></i></span>
                                    Laporan
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                                    <span class="sidebar-icon"><i class="bi bi-chat-square-dots"></i></span>
                                    Feedback
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="sidebar-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.create') }}">
                                    <span class="sidebar-icon"><i class="bi bi-chat-square-dots"></i></span>
                                    Feedback
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page scripts (charts, page-specific JS) --}}
    @yield('scripts')
</body>
</html>
