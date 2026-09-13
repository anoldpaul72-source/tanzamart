<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.sidebar_customer_title')) - TanzaMart</title>
    <style>
        :root {
            --primary: #00bcd4;
            --primary-dark: #0097a7;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #00bcd4;
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

        /* CUSTOMER LEFT SIDEBAR */
        .customer-sidebar {
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
            background: linear-gradient(135deg, #00bcd4, #0097a7);
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
            background: #00bcd4;
            color: #000;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* USER PROFILE CARD */
        .customer-widget {
            margin: 16px;
            padding: 12px 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--radius-md);
        }

        .customer-widget .user-hello {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .customer-widget .user-name {
            font-size: 14px;
            font-weight: 800;
            color: #ffffff;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-menu {
            flex: 1;
            padding: 8px 12px;
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
            background: linear-gradient(135deg, #00bcd4, #0097a7);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 188, 212, 0.35);
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
            background: #00bcd4;
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
        .customer-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        /* TOPBAR */
        .customer-topbar {
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

        .topbar-btn {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .topbar-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: var(--primary);
        }

        .customer-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #00bcd4;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
        }

        /* PAGE BODY */
        .customer-content {
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
            .customer-sidebar {
                transform: translateX(-100%);
            }
            .customer-sidebar.open {
                transform: translateX(0);
            }
            .customer-main {
                margin-left: 0;
                width: 100%;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .sidebar-overlay.active {
                display: block;
            }
            .customer-content {
                padding: 18px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- CUSTOMER LEFT SIDEBAR -->
    <aside class="customer-sidebar" id="customerSidebar">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <div class="logo-icon">🛒</div>
            <div>
                <div class="brand-text">TanzaMart</div>
                <span class="brand-badge">{{ __('messages.sidebar_role_customer') }}</span>
            </div>
        </a>

        @php
            $currentCustomer = Auth::user();
        @endphp

        <div class="customer-widget">
            <div class="user-hello">{{ __('messages.sidebar_welcome') }}</div>
            <div class="user-name">👤 {{ $currentCustomer ? $currentCustomer->name : 'Mteja' }}</div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-category">{{ __('messages.sidebar_customer_title') }}</li>

            <li class="menu-item">
                <a href="{{ route('order.my_orders') }}" class="menu-link {{ request()->routeIs('order.my_orders') ? 'active' : '' }}">
                    <span class="icon">📦</span>
                    <span>{{ __('messages.sidebar_cust_orders') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('order.track') }}" class="menu-link {{ request()->routeIs('order.track') ? 'active' : '' }}">
                    <span class="icon">🚚</span>
                    <span>{{ __('messages.sidebar_cust_track') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('cart.index') }}" class="menu-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                    <span class="icon">🛒</span>
                    <span>{{ __('messages.sidebar_cust_cart') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('support.index') }}" class="menu-link {{ request()->routeIs('support.index') ? 'active' : '' }}">
                    <span class="icon">💬</span>
                    <span>{{ __('messages.sidebar_cust_support') }}</span>
                </a>
            </li>

            <li class="menu-category">{{ __('messages.sidebar_cust_shop') }}</li>

            <li class="menu-item">
                <a href="{{ route('shop.products') }}" class="menu-link {{ request()->routeIs('shop.products*') ? 'active' : '' }}">
                    <span class="icon">🛍️</span>
                    <span>{{ __('messages.sidebar_cust_shop') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('home') }}" class="menu-link">
                    <span class="icon">🏠</span>
                    <span>{{ __('messages.nav_home') }}</span>
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

            @if(Auth::check())
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <span>🚪</span> {{ __('messages.sidebar_logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-logout" style="background:#00bcd4; text-decoration:none;">
                    <span>🔑</span> {{ __('messages.nav_login') }}
                </a>
            @endif
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="customer-main">
        <!-- TOPBAR -->
        <header class="customer-topbar">
            <div class="topbar-left">
                <button type="button" class="btn-mobile-toggle" onclick="toggleSidebar()">☰</button>
                <div class="topbar-title">@yield('page_title', 'TanzaMart')</div>
            </div>

            <div class="topbar-right">
                <button type="button" onclick="openChangePasswordModal()" class="topbar-btn" style="background: rgba(0, 188, 212, 0.1); color: #0891b2; border: 1px solid rgba(0, 188, 212, 0.35); font-weight: 700; cursor: pointer;" title="Badili Nenosiri">
                    <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
                </button>

                <a href="{{ route('cart.index') }}" class="topbar-btn">
                    <span>🛒</span> {{ __('messages.sidebar_cust_cart') }}
                </a>

                <div class="topbar-btn" style="border:none; padding:0;">
                    <div class="customer-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="customer-content">
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
            const sidebar = document.getElementById('customerSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
    @yield('scripts')
</body>
</html>
