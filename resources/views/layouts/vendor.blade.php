<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.sidebar_vendor_title')) - TanzaMart</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #1a237e;
            --sidebar-hover: #283593;
            --sidebar-active: #2563eb;
            --sidebar-text: #c5cae9;
            --body-bg: #f4f6f9;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
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

        /* VENDOR LEFT SIDEBAR */
        .vendor-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #101935 0%, #1e1b4b 100%);
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
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2563eb, #0284c7);
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
            background: #2563eb;
            color: #000;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* STORE WALLET BADGE */
        .store-widget {
            margin: 16px;
            padding: 12px 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-md);
        }

        .store-widget .shop-name {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .store-widget .wallet-val {
            font-size: 14px;
            font-weight: 800;
            color: #22c55e;
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            color: #64748b;
            padding: 10px 12px 6px;
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
            color: #cbd5e1;
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
            background-color: rgba(255,255,255,0.08);
            color: #ffffff;
            transform: translateX(3px);
        }

        .menu-link.active {
            background: linear-gradient(135deg, #2563eb, #0284c7);
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
            background: rgba(255,255,255,0.06);
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
            color: #000;
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
        .vendor-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        /* TOPBAR */
        .vendor-topbar {
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
            gap: 14px;
        }

        .topbar-btn {
            font-size: 13px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .topbar-btn.view-shop {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .topbar-btn.view-shop:hover {
            background: #0284c7;
            color: #ffffff;
        }

        .vendor-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
        }

        .vendor-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #1a237e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
        }

        /* PAGE BODY */
        .vendor-content {
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
            .vendor-sidebar {
                transform: translateX(-100%);
            }
            .vendor-sidebar.open {
                transform: translateX(0);
            }
            .vendor-main {
                margin-left: 0;
                width: 100%;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .sidebar-overlay.active {
                display: block;
            }
            .vendor-content {
                padding: 18px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- VENDOR LEFT SIDEBAR -->
    <aside class="vendor-sidebar" id="vendorSidebar">
        <a href="{{ route('vendor.dashboard') }}" class="sidebar-brand">
            <div class="logo-icon">🏪</div>
            <div>
                <div class="brand-text">TanzaMart</div>
                <span class="brand-badge">{{ __('messages.sidebar_role_vendor') }}</span>
            </div>
        </a>

        @php
            $currentVendor = Auth::user();
            $shopTitle = $currentVendor ? ($currentVendor->shop_name ?: $currentVendor->name) : 'Store';
            $walletAmount = $currentVendor ? $currentVendor->balance : 0;
        @endphp

        <div class="store-widget">
            <div class="shop-name">🏢 {{ $shopTitle }}</div>
            <div class="wallet-val">
                <span style="font-size: 11px; color: #94a3b8;">{{ __('messages.sidebar_balance') }}</span>
                <span>TZS {{ number_format($walletAmount) }}</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-category">{{ __('messages.sidebar_vendor_title') }}</li>

            <li class="menu-item">
                <a href="{{ route('vendor.dashboard') }}" class="menu-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span>
                    <span>{{ __('messages.sidebar_dashboard') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('vendor.products') }}" class="menu-link {{ request()->routeIs('vendor.products*') ? 'active' : '' }}">
                    <span class="icon">📦</span>
                    <span>{{ __('messages.sidebar_my_products') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('vendor.orders') }}" class="menu-link {{ request()->routeIs('vendor.orders*') ? 'active' : '' }}">
                    <span class="icon">🛒</span>
                    <span>{{ __('messages.sidebar_vendor_orders') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('vendor.subscribe.page') }}" class="menu-link {{ request()->routeIs('vendor.subscribe*') ? 'active' : '' }}">
                    <span class="icon">⭐</span>
                    <span>{{ __('messages.sidebar_vendor_sub') }}</span>
                </a>
            </li>

            <li class="menu-category">{{ __('messages.sidebar_cust_shop') }}</li>

            <li class="menu-item">
                <a href="{{ route('shop.products') }}" target="_blank" class="menu-link">
                    <span class="icon">🌐</span>
                    <span>{{ __('messages.sidebar_vendor_view_shop') }}</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('home') }}" target="_blank" class="menu-link">
                    <span class="icon">🛍️</span>
                    <span>{{ __('messages.sidebar_cust_shop') }}</span>
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
    <div class="vendor-main">
        <!-- TOPBAR -->
        <header class="vendor-topbar">
            <div class="topbar-left">
                <button type="button" class="btn-mobile-toggle" onclick="toggleSidebar()">☰</button>
                <div class="topbar-title">@yield('page_title', 'TanzaMart Vendor Portal')</div>
            </div>

            <div class="topbar-right">
                <button type="button" onclick="openChangePasswordModal()" class="topbar-btn" style="background: rgba(37, 99, 235, 0.12); color: #0284c7; border: 1px solid rgba(37, 99, 235, 0.35); font-weight: 700; cursor: pointer;" title="Badili Nenosiri">
                    <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
                </button>

                <a href="{{ route('shop.products') }}" target="_blank" class="topbar-btn view-shop">
                    <span>👁️</span> {{ __('messages.sidebar_vendor_view_shop') }}
                </a>

                <div class="vendor-profile">
                    <div class="vendor-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'V', 0, 1)) }}</div>
                    <span>{{ Auth::user()->name ?? 'Vendor' }}</span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="vendor-content">
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
            const sidebar = document.getElementById('vendorSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
    @yield('scripts')
</body>
</html>
