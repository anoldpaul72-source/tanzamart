<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.sidebar_admin_title')) - TanzaMart</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-md: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 14px rgba(0,0,0,0.06);
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-main);
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .admin-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 12px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            font-weight: bold;
        }

        .sidebar-brand .brand-text {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .sidebar-brand .brand-badge {
            background: #f59e0b;
            color: #000;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
            list-style: none;
        }

        .menu-category {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
            padding: 12px 12px 6px;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .menu-link .icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .menu-link:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(3px);
        }

        .menu-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
        }

        .lang-switch-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            background: #1e293b;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .lang-switch-box span {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
        }

        .lang-buttons {
            display: flex;
            gap: 4px;
        }

        .btn-lang {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 800;
            border-radius: 4px;
            text-decoration: none;
            color: #94a3b8;
            background: transparent;
            transition: all 0.2s;
        }

        .btn-lang.active {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-logout {
            width: 100%;
            background: #ef4444;
            color: white;
            border: none;
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        /* MAIN CONTENT */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        /* TOPBAR */
        .admin-topbar {
            height: 64px;
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-dark);
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-link {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .topbar-link:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: var(--primary);
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
        }

        /* PAGE BODY */
        .admin-content {
            flex: 1;
            padding: 28px;
        }

        /* FLASH MESSAGES */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #15803d;
            border-left: 4px solid #10b981;
        }

        .alert-danger, .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }

        /* OVERLAY FOR MOBILE */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                width: 100%;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .sidebar-overlay.active {
                display: block;
            }
            .admin-content {
                padding: 18px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ADMIN LEFT SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="logo-icon">⚡</div>
            <div>
                <div class="brand-text">TanzaMart</div>
                <span class="brand-badge">{{ __('messages.sidebar_role_admin') }}</span>
            </div>
        </a>

        <ul class="sidebar-menu">
            <li class="menu-category">{{ __('messages.sidebar_admin_title') }}</li>

            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span>
                    <span>{{ __('messages.sidebar_dashboard') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.products') }}" class="menu-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <span class="icon">📦</span>
                    <span>{{ __('messages.sidebar_products') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.orders') }}" class="menu-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <span class="icon">🛒</span>
                    <span>{{ __('messages.sidebar_orders') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.escrow_payments') }}" class="menu-link {{ request()->routeIs('admin.escrow_payments') ? 'active' : '' }}">
                    <span class="icon">🔒</span>
                    <span>{{ __('messages.sidebar_escrow') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.withdrawals') }}" class="menu-link {{ request()->routeIs('admin.withdrawals') ? 'active' : '' }}">
                    <span class="icon">💳</span>
                    <span>{{ __('messages.sidebar_withdrawals') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.disputes') }}" class="menu-link {{ request()->routeIs('admin.disputes') ? 'active' : '' }}">
                    <span class="icon">⚠️</span>
                    <span>{{ __('messages.sidebar_disputes') }}</span>
                </a>
            </li>

            <li class="menu-category">{{ __('messages.sidebar_vendors') }} & {{ __('messages.sidebar_users') }}</li>

            <li class="menu-item">
                <a href="{{ route('admin.vendors') }}" class="menu-link {{ request()->routeIs('admin.vendors') ? 'active' : '' }}">
                    <span class="icon">🏪</span>
                    <span>{{ __('messages.sidebar_vendors') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.users') }}" class="menu-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="icon">👥</span>
                    <span>{{ __('messages.sidebar_users') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.reports') }}" class="menu-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <span class="icon">📈</span>
                    <span>{{ __('messages.sidebar_reports') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.backup.export') }}" class="menu-link" style="color: #34d399; font-weight: 700;">
                    <span class="icon">💾</span>
                    <span>Export Backup Data</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" onclick="openChangePasswordModal()" class="menu-link" style="color: #38bdf8; font-weight: 700;">
                    <span class="icon">🔑</span>
                    <span>{{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="lang-switch-box">
                <span>🌐 {{ __('messages.sidebar_switch_lang') }}</span>
                <div class="lang-buttons">
                    <a href="{{ route('lang.switch', 'sw') }}" class="btn-lang {{ app()->getLocale() === 'sw' ? 'active' : '' }}">SW</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="btn-lang {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <span>🚪</span> {{ __('messages.sidebar_logout') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button type="button" class="btn-mobile-toggle" onclick="toggleSidebar()">☰</button>
                <div class="topbar-title">@yield('page_title', 'TanzaMart Admin Portal')</div>
            </div>

            <div class="topbar-right">
                <button type="button" onclick="openChangePasswordModal()" class="topbar-link" style="background: rgba(37, 99, 235, 0.1); color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.3); font-weight: 700; cursor: pointer;" title="Badili Nenosiri">
                    <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
                </button>

                <a href="{{ route('admin.backup.export') }}" class="topbar-link" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.35); font-weight: 700;" title="Download full system data backup">
                    <span>💾</span> Export Backup
                </a>

                <a href="{{ route('home') }}" target="_blank" class="topbar-link">
                    <span>🛍️</span> {{ __('messages.sidebar_cust_shop') }}
                </a>

                <div class="admin-badge">
                    <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- CHANGE PASSWORD MODAL -->
    @include('partials.change_password_modal')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
    @yield('scripts')
</body>
</html>
