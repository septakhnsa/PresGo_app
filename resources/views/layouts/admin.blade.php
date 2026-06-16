<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PresGo Admin Panel</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --tosca: #14532D;
            --tosca-light: #15803D;
            --mint: #DCFCE7;
            --gold: #FFD54F;
            --gold-dark: #F59E0B;
            --bg-light: #F8FAFC;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-width: 3px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Responsive Layout Grid */
        .admin-container {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: 100vh;
        }

        /* Sidebar Styling (Brutalist Modern) */
        .sidebar {
            background-color: var(--tosca);
            color: white;
            border-right: var(--border-width) solid var(--text-dark);
            display: flex;
            flex-direction: column;
            padding: 24px 12px;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 24px;
            border-bottom: 2px dashed rgba(255, 255, 255, 0.2);
            margin-bottom: 24px;
        }

        .logo-box {
            background-color: var(--gold);
            color: var(--tosca);
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 900;
            border: 2.5px solid var(--text-dark);
            box-shadow: -3px 3px 0px var(--text-dark);
        }

        .logo-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: white;
        }

        .logo-text p {
            font-size: 10px;
            color: var(--gold);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            white-space: nowrap;
        }

        .nav-item a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .nav-item.active a {
            background-color: var(--gold);
            color: var(--tosca);
            border: 2.5px solid var(--text-dark);
            box-shadow: -4px 4px 0px var(--text-dark);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 2px dashed rgba(255, 255, 255, 0.2);
        }

        /* Global Table Styling (Brutalist Modern) */
        .presgo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .presgo-table th {
            background-color: var(--mint);
            color: var(--tosca);
            text-align: left;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 800;
            border: 2px solid var(--text-dark);
            white-space: nowrap;
        }

        .presgo-table td {
            padding: 8px 12px;
            font-size: 11.5px;
            font-weight: 600;
            border: 2px solid var(--text-dark);
            background-color: white;
            color: var(--text-dark);
        }

        .presgo-table tr:hover td {
            background-color: var(--bg-light);
        }

        /* Prevents badges and tags inside table cells from wrapping awkwardly */
        .presgo-table td span, 
        .presgo-table td strong, 
        .presgo-table td a,
        .presgo-table td badge {
            white-space: nowrap;
        }

        .logout-btn {
            width: 100%;
            padding: 12px;
            background-color: #EF4444;
            color: white;
            border: 2.5px solid var(--text-dark);
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: -3px 3px 0px var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.1s ease;
        }

        .logout-btn:active {
            transform: translate(-2px, 2px);
            box-shadow: -1px 1px 0px var(--text-dark);
        }

        /* Main Content Wrapper */
        .main-content {
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top Header Styling */
        .top-navbar {
            background-color: white;
            border-bottom: var(--border-width) solid var(--text-dark);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .welcome-msg h2 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .welcome-msg p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-badge {
            background-color: var(--mint);
            color: var(--tosca);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            border: 1.5px solid var(--tosca);
        }

        /* Page Content Frame */
        .content-body {
            padding: 32px;
            flex-grow: 1;
        }

        /* Brutalist UI Cards & Styling Utilities */
        .presgo-card {
            background: white;
            border: var(--border-width) solid var(--text-dark);
            border-radius: 20px;
            padding: 24px;
            box-shadow: -6px 6px 0px var(--text-dark);
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--tosca);
        }

        /* Mobile Header & Nav Menu (Hidden on Desktop) */
        .mobile-header, .mobile-nav-menu {
            display: none;
        }

        /* Media Queries for WebView inside Flutter & Mobile Browsers */
        @media (max-width: 900px) {
            .admin-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none; /* Hide standard sidebar on mobile */
            }

            .top-navbar {
                display: none; /* Hide standard top header */
            }

            .mobile-header {
                display: block;
                background-color: var(--tosca);
                color: white;
                padding: 14px 20px;
                border-bottom: var(--border-width) solid var(--text-dark);
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .mobile-header-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .mobile-nav-menu {
                display: flex;
                background-color: white;
                border-bottom: 2px solid var(--text-dark);
                padding: 10px;
                gap: 8px;
                overflow-x: auto;
                position: sticky;
                top: 73px; /* Right below mobile header */
                z-index: 99;
                white-space: nowrap;
            }

            .mobile-nav-link {
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 700;
                text-decoration: none;
                color: var(--text-muted);
                border-radius: 8px;
                border: 1.5px solid transparent;
            }

            .mobile-nav-link.active {
                background-color: var(--gold);
                color: var(--tosca);
                border: 2px solid var(--text-dark);
                box-shadow: -2px 2px 0px var(--text-dark);
            }

            .content-body {
                padding: 16px;
            }

            .presgo-card {
                padding: 16px;
                box-shadow: -4px 4px 0px var(--text-dark);
                margin-bottom: 16px;
            }

            .presgo-table th, .presgo-table td {
                padding: 8px 10px !important;
                font-size: 11px !important;
            }

            .presgo-table td strong {
                font-size: 11px !important;
            }

            .presgo-table td span {
                font-size: 10px !important;
            }

            .status-badge, .method-badge, .location-badge, .day-badge {
                padding: 3px 6px !important;
                font-size: 10px !important;
            }

            .photo-preview {
                width: 40px !important;
                height: 40px !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="admin-container">
        <!-- ─── SIDEBAR (DESKTOP) ─── -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-box">P</div>
                <div class="logo-text">
                    <h1>PresGo</h1>
                    <p>Web Admin Panel</p>
                </div>
            </div>
            
            <nav style="flex-grow: 1;">
                <ul class="nav-list">
                    <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa-solid fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.jadwal') ? 'active' : '' }}">
                        <a href="{{ route('admin.jadwal') }}">
                            <i class="fa-solid fa-calendar-days"></i> Jadwal Kuliah
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.mahasiswa') ? 'active' : '' }}">
                        <a href="{{ route('admin.mahasiswa') }}">
                            <i class="fa-solid fa-graduation-cap"></i> Mahasiswa
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.presensi') ? 'active' : '' }}">
                        <a href="{{ route('admin.presensi') }}">
                            <i class="fa-solid fa-circle-check"></i> Riwayat Presensi
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- ─── MAIN CONTENT WRAPPER ─── -->
        <main class="main-content">
            
            <!-- ─── MOBILE HEADER (WEBVIEW / SMARTPHONE) ─── -->
            <div class="mobile-header">
                <div class="mobile-header-content">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div class="logo-box" style="width: 32px; height: 32px; font-size: 18px; border-radius: 6px; box-shadow: -2px 2px 0px var(--text-dark);">P</div>
                        <span style="font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 800; letter-spacing: 0.5px;">PresGo</span>
                    </div>
                    <span class="admin-badge" style="font-size: 9px; padding: 4px 8px; border-radius: 8px;">Admin</span>
                </div>
            </div>

            <!-- ─── MOBILE NAV BAR (ONLY WEBVIEW) ─── -->
            <div class="mobile-nav-menu">
                <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="{{ route('admin.jadwal') }}" class="mobile-nav-link {{ Route::is('admin.jadwal') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i> Jadwal
                </a>
                <a href="{{ route('admin.mahasiswa') }}" class="mobile-nav-link {{ Route::is('admin.mahasiswa') ? 'active' : '' }}">
                    <i class="fa-solid fa-graduation-cap"></i> Mahasiswa
                </a>
                <a href="{{ route('admin.presensi') }}" class="mobile-nav-link {{ Route::is('admin.presensi') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-check"></i> Presensi
                </a>
            </div>

            <!-- ─── TOP NAVBAR (DESKTOP) ─── -->
            <header class="top-navbar">
                <div class="welcome-msg">
                    <h2>Panel Manajemen Presensi</h2>
                    <p>Kelola dan pantau aktivitas presensi secara terpusat.</p>
                </div>
                <div class="admin-profile">
                    <span class="admin-badge">Administrator</span>
                    <strong style="font-size: 14px;">{{ Auth::user()->name }}</strong>
                </div>
            </header>

            <!-- ─── PAGE BODY ─── -->
            <section class="content-body">
                @yield('content')
            </section>

        </main>
    </div>

    @yield('scripts')
</body>
</html>
