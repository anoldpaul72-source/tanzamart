<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TanzaMart - ' . __('messages.tagline'))</title>

    <meta name="theme-color" content="#2563eb">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-width: 280px;
            --sidebar-bg: #0b1329;
            --sidebar-header: #070d1d;
            --sidebar-card: rgba(255, 255, 255, 0.04);
            --sidebar-card-border: rgba(255, 255, 255, 0.08);
            --sidebar-text: #94a3b8;
            --sidebar-hover: rgba(37, 99, 235, 0.12);
            --text-main: #1e293b;
            --bg-main: #f8fafc;
            --header-bg: #111827;
            --header-height: 64px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* SIDEBAR BACKDROP FOR MOBILE */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .sidebar-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* MAIN LEFT SIDEBAR */
        .main-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-card-border);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.18);
            transition: var(--transition);
            transform: translateX(0);
        }

        /* Closed state for sidebar on desktop */
        body.sidebar-closed .main-sidebar {
            transform: translateX(-100%);
        }

        /* Sidebar Brand & Close Header */
        .sidebar-brand-box {
            height: var(--header-height);
            padding: 0 20px;
            background: var(--sidebar-header);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-card-border);
        }

        .sidebar-brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand span {
            color: var(--primary);
        }

        .sidebar-close-btn {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: #cbd5e1;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: var(--transition);
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Sidebar Scrollable Body */
        .sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px 14px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar-body::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        /* User / Guest Status Card */
        .user-status-card {
            background: var(--sidebar-card);
            border: 1px solid var(--sidebar-card-border);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .user-profile-flex {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            color: #0b1329;
            font-weight: 800;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
            flex-shrink: 0;
        }

        .user-meta-info {
            overflow: hidden;
            flex: 1;
        }

        .user-meta-name {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .role-pill {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-pill.admin {
            background: rgba(234, 179, 8, 0.18);
            color: #facc15;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        .role-pill.vendor {
            background: rgba(34, 197, 94, 0.18);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .role-pill.customer {
            background: rgba(37, 99, 235, 0.18);
            color: #38bdf8;
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .guest-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .guest-btn {
            flex: 1;
            padding: 8px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            border-radius: 6px;
            transition: var(--transition);
        }

        .guest-btn.login {
            background: var(--primary);
            color: #0b1329;
        }
        .guest-btn.login:hover {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .guest-btn.register {
            background: rgba(255, 255, 255, 0.08);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .guest-btn.register:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        /* Nav Section Titles */
        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            padding: 8px 10px 4px;
            margin-top: 10px;
        }

        /* Nav Link Items */
        .sidebar-nav-list {
            list-style: none;
            margin-bottom: 12px;
        }

        .sidebar-nav-item {
            margin-bottom: 3px;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #cbd5e1;
            transition: var(--transition);
        }

        .sidebar-nav-link-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-icon {
            font-size: 17px;
            width: 22px;
            text-align: center;
            display: inline-block;
        }

        .sidebar-nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(4px);
        }

        .sidebar-nav-link.active {
            background: rgba(37, 99, 235, 0.2);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }

        .count-badge {
            background: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .count-badge.primary {
            background: var(--primary);
            color: #0b1329;
        }

        /* Sidebar Category Dropdown Section */
        .sidebar-category-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 9px 12px;
            color: #e2e8f0;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 14px;
            margin-bottom: 8px;
            outline: none;
        }

        .sidebar-category-toggle:hover {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.35);
            color: #2563eb;
        }

        .sidebar-category-toggle.open {
            background: rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.4);
            color: #ffffff;
        }

        .category-toggle-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-chevron {
            font-size: 11px;
            transition: transform 0.3s ease;
            color: #94a3b8;
            font-weight: 800;
        }

        .sidebar-category-toggle.open .category-chevron {
            transform: rotate(180deg);
            color: #2563eb;
        }

        .sidebar-category-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .sidebar-category-dropdown.open {
            max-height: 1200px;
            opacity: 1;
        }

        /* Category Select Box inside sidebar */
        .sidebar-category-select-wrapper {
            margin: 4px 0 10px 0;
            position: relative;
        }

        .sidebar-category-select {
            width: 100%;
            padding: 9px 32px 9px 12px;
            background: #0f172a;
            border: 1.5px solid rgba(37, 99, 235, 0.35);
            border-radius: 8px;
            color: #38bdf8;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232563eb'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
        }

        .sidebar-category-select:hover, .sidebar-category-select:focus {
            border-color: #2563eb;
            background-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .sidebar-category-select option {
            background: #0f172a;
            color: #ffffff;
            padding: 8px;
        }

        /* Quick Support Banner in Sidebar */
        .sidebar-support-card {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(15, 23, 42, 0.4));
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: 10px;
            padding: 12px;
            margin-top: 15px;
            text-align: center;
        }

        .sidebar-support-card h5 {
            font-size: 13px;
            color: #ffffff;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .sidebar-support-card p {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .sidebar-support-card a {
            display: inline-block;
            font-size: 12px;
            color: var(--primary);
            font-weight: bold;
            padding: 4px 10px;
            background: rgba(37, 99, 235, 0.15);
            border-radius: 6px;
            transition: var(--transition);
        }

        .sidebar-support-card a:hover {
            background: var(--primary);
            color: #0b1329;
        }

        /* Sidebar Footer (Language & Logout) */
        .sidebar-footer {
            padding: 14px 16px;
            background: var(--sidebar-header);
            border-top: 1px solid var(--sidebar-card-border);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-lang-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .lang-pill-btn {
            flex: 1;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            border: 1px solid var(--sidebar-card-border);
            color: #94a3b8;
            background: rgba(255, 255, 255, 0.03);
            transition: var(--transition);
        }

        .lang-pill-btn.active, .lang-pill-btn:hover {
            border-color: var(--primary);
            color: #ffffff;
            background: rgba(37, 99, 235, 0.15);
        }

        .sidebar-logout-btn {
            width: 100%;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            padding: 8px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .sidebar-logout-btn:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* APP BODY WRAPPER (RIGHT OF SIDEBAR) */
        .app-body-wrapper {
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            width: calc(100% - var(--sidebar-width));
        }

        body.sidebar-closed .app-body-wrapper {
            margin-left: 0;
            width: 100%;
        }

        /* TOP HEADER */
        header {
            height: var(--header-height);
            background: var(--header-bg);
            color: white;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        }

        .header-left-flex {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: var(--transition);
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary);
            color: #0b1329;
            border-color: var(--primary);
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
            padding: 6px 10px;
            border-radius: 6px;
        }

        nav a:hover {
            color: var(--primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .header-cart-btn {
            background: rgba(37, 99, 235, 0.15);
            color: var(--primary) !important;
            border: 1px solid rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px !important;
        }

        .header-cart-btn:hover {
            background: var(--primary) !important;
            color: #0b1329 !important;
        }

        .lang-select {
            padding: 6px 10px;
            background: #1e293b;
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.4);
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            outline: none;
            transition: var(--transition);
        }

        .lang-select:hover {
            background: var(--primary);
            color: #0b1329;
        }

        /* Flash Alerts */
        .flash-container {
            max-width: 1200px;
            margin: 15px auto 0;
            padding: 0 20px;
            width: 100%;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        main {
            flex: 1;
            width: 100%;
        }

        /* FOOTER */
        footer {
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 35px 20px;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1024px) {
            .main-sidebar {
                transform: translateX(-100%);
            }
            .main-sidebar.mobile-open {
                transform: translateX(0);
            }
            .app-body-wrapper {
                margin-left: 0;
                width: 100%;
            }
            nav .nav-link-optional {
                display: none;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 0 16px;
            }
            nav {
                gap: 8px;
            }
            nav a {
                font-size: 13px;
                padding: 5px 8px;
            }
            .logo {
                font-size: 22px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $sidebarCategories = \App\Models\Category::withCount('products')->get();
        $cartCount = count(session('cart', []));
    @endphp

    <!-- BACKDROP FOR MOBILE SIDEBAR -->
    <div id="sidebar-backdrop" class="sidebar-backdrop" onclick="toggleMainSidebar()"></div>

    <!-- MAIN LEFT NAVIGATION SIDEBAR -->
    <aside id="main-sidebar" class="main-sidebar">
        <!-- Sidebar Header Brand -->
        <div class="sidebar-brand-box">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <span>🛒</span> Tanza<span>Mart</span>
            </a>
            <button class="sidebar-close-btn" onclick="toggleMainSidebar()" title="Close Sidebar">
                ✕
            </button>
        </div>

        <!-- Scrollable Sidebar Body -->
        <div class="sidebar-body">
            
            <!-- User Status / Quick Login Card -->
            <div class="user-status-card">
                @auth
                    <div class="user-profile-flex">
                        <div class="user-avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="user-meta-info">
                            <div class="user-meta-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                @if(Auth::user()->isAdmin())
                                    <span class="role-pill admin">⚡ Admin</span>
                                @elseif(Auth::user()->isVendor())
                                    <span class="role-pill vendor">🏪 Vendor</span>
                                @else
                                    <span class="role-pill customer">🛍️ Customer</span>
                                @endif
                                <button type="button" onclick="openChangePasswordModal()" style="background: none; border: none; color: #2563eb; font-size: 11px; font-weight: 700; cursor: pointer; padding: 2px 4px; display: inline-flex; align-items: center; gap: 3px;" title="Badili Nenosiri">
                                    <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="font-size: 13px; font-weight: 700; color: #ffffff; margin-bottom: 2px;">
                        👋 {{ __('messages.hero_title') }}
                    </div>
                    <div style="font-size: 12px; color: #94a3b8;">
                        {{ __('messages.tagline') }}
                    </div>
                    <div class="guest-actions">
                        <a href="{{ route('login') }}" class="guest-btn login">🔑 {{ __('messages.nav_login') }}</a>
                        <a href="{{ route('register') }}" class="guest-btn register">📝 {{ __('messages.nav_register') }}</a>
                    </div>
                @endauth
            </div>

            <!-- Role Specific Portals -->
            @auth
                @if(Auth::user()->isAdmin())
                    <div class="sidebar-section-title">⚡ {{ __('messages.nav_admin') }}</div>
                    <ul class="sidebar-nav-list">
                        <li class="sidebar-nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">📊</span> Dashibodi ya Admin</span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ route('admin.orders') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">📦</span> Oda Zote (Orders)</span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ route('admin.vendors') }}" class="sidebar-nav-link {{ request()->routeIs('admin.vendors') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">🏪</span> Wauzaji (Vendors)</span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ route('admin.disputes') }}" class="sidebar-nav-link {{ request()->routeIs('admin.disputes') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">⚖️</span> Migogoro (Disputes)</span>
                            </a>
                        </li>
                    </ul>
                @elseif(Auth::user()->isVendor())
                    <div class="sidebar-section-title">🏪 {{ __('messages.nav_vendor') }}</div>
                    <ul class="sidebar-nav-list">
                        <li class="sidebar-nav-item">
                            <a href="{{ route('vendor.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">📈</span> Dashibodi ya Muuzaji</span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ route('vendor.orders') }}" class="sidebar-nav-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">📦</span> Oda za Wateja</span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ route('vendor.products') }}" class="sidebar-nav-link {{ request()->routeIs('vendor.products') ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left"><span class="nav-icon">🏷️</span> Bidhaa Zangu</span>
                            </a>
                        </li>
                    </ul>
                @endif
            @endauth

            <!-- Store Main Navigation -->
            <div class="sidebar-section-title">🧭 {{ app()->getLocale() == 'sw' ? 'Urambazaji wa Duka' : 'Store Navigation' }}</div>
            <ul class="sidebar-nav-list">
                <li class="sidebar-nav-item">
                    <a href="{{ route('home') }}" class="sidebar-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">🏠</span> {{ __('messages.nav_home') }}</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('shop.products') }}" class="sidebar-nav-link {{ request()->routeIs('shop.products') && !request('category') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">🛍️</span> {{ __('messages.all_products') }}</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('cart.index') }}" class="sidebar-nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">🛒</span> {{ __('messages.nav_cart') }}</span>
                        <span class="count-badge primary">{{ $cartCount }}</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('order.track') }}" class="sidebar-nav-link {{ request()->routeIs('order.track') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">📍</span> {{ __('messages.nav_track') }}</span>
                    </a>
                </li>
                @auth
                    <li class="sidebar-nav-item">
                        <a href="{{ route('order.my_orders') }}" class="sidebar-nav-link {{ request()->routeIs('order.my_orders') ? 'active' : '' }}">
                            <span class="sidebar-nav-link-left"><span class="nav-icon">📦</span> {{ __('messages.nav_my_orders') }}</span>
                        </a>
                    </li>
                @endauth
                <li class="sidebar-nav-item">
                    <a href="{{ route('support.index') }}" class="sidebar-nav-link {{ request()->routeIs('support.index') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">💬</span> {{ __('messages.nav_support') }}</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('about') }}" class="sidebar-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <span class="sidebar-nav-link-left"><span class="nav-icon">ℹ️</span> {{ app()->getLocale() == 'sw' ? 'Kuhusu Sisi' : 'About Us' }}</span>
                    </a>
                </li>
            </ul>

            <!-- Product Categories Section with Drop Down -->
            <button type="button" class="sidebar-category-toggle open" id="categoryDropdownBtn" onclick="toggleCategoriesDropdown()" title="{{ app()->getLocale() == 'sw' ? 'Bofya kufungua au kufunga kategoria' : 'Click to toggle categories' }}">
                <div class="category-toggle-left">
                    <span style="font-size: 14px;">🏷️</span>
                    <span>{{ __('messages.categories') }}</span>
                    <span class="count-badge primary">{{ $sidebarCategories->count() }}</span>
                </div>
                <span class="category-chevron" id="categoryChevron">▼</span>
            </button>

            <div class="sidebar-category-dropdown open" id="categoryDropdownContent">
                <!-- Dropdown Select Picker -->
                <div class="sidebar-category-select-wrapper">
                    <select class="sidebar-category-select" onchange="if(this.value) window.location.href=this.value;" title="{{ app()->getLocale() == 'sw' ? 'Chagua Kategoria Moja kwa Moja' : 'Quick Category Selector' }}">
                        <option value="{{ route('shop.products') }}">
                            🏷️ {{ app()->getLocale() == 'sw' ? '-- Chagua Kategoria Zote (' . $sidebarCategories->count() . ') --' : '-- View All Categories (' . $sidebarCategories->count() . ') --' }}
                        </option>
                        @foreach($sidebarCategories as $cat)
                            <option value="{{ route('shop.products', ['category' => $cat->slug]) }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->icon ?? '📦' }} {{ $cat->name }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category List Items -->
                <ul class="sidebar-nav-list" style="margin-bottom: 8px;">
                    <li class="sidebar-nav-item">
                        <a href="{{ route('shop.products') }}" 
                           class="sidebar-nav-link {{ request()->routeIs('shop.products') && !request('category') ? 'active' : '' }}">
                            <span class="sidebar-nav-link-left">
                                <span class="nav-icon">✨</span> 
                                <span>{{ app()->getLocale() == 'sw' ? 'Bidhaa Zote (Kategoria Zote)' : 'All Products (All Categories)' }}</span>
                            </span>
                            <span class="count-badge primary">{{ $sidebarCategories->sum('products_count') }}</span>
                        </a>
                    </li>
                    @foreach($sidebarCategories as $cat)
                        <li class="sidebar-nav-item">
                            <a href="{{ route('shop.products', ['category' => $cat->slug]) }}" 
                               class="sidebar-nav-link {{ request('category') == $cat->slug ? 'active' : '' }}">
                                <span class="sidebar-nav-link-left">
                                    <span class="nav-icon">{{ $cat->icon ?? '📦' }}</span> 
                                    <span>{{ $cat->name }}</span>
                                </span>
                                @if($cat->products_count > 0)
                                    <span class="count-badge">{{ $cat->products_count }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Support & Assistance Card -->
            <div class="sidebar-support-card">
                <h5>📞 {{ app()->getLocale() == 'sw' ? 'Unahitaji Msaada?' : 'Need Help?' }}</h5>
                <p>{{ app()->getLocale() == 'sw' ? 'Wasiliana nasi muda wowote:' : 'Call our direct support line:' }}</p>
                <a href="tel:+255621530804">+255 621 530 804</a>
            </div>

        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-lang-row">
                <a href="?lang=sw" class="lang-pill-btn {{ app()->getLocale() == 'sw' ? 'active' : '' }}">🇹🇿 Kiswahili</a>
                <a href="?lang=en" class="lang-pill-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">🇬🇧 English</a>
            </div>

            @auth
                <button type="button" onclick="openChangePasswordModal()" class="sidebar-logout-btn" style="background: rgba(37, 99, 235, 0.12); border: 1px solid rgba(37, 99, 235, 0.3); color: #2563eb; margin-bottom: 6px;" title="Badili Nenosiri la akaunti">
                    <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <span>🚪</span> {{ __('messages.nav_logout') }}
                    </button>
                </form>
            @endauth
        </div>
    </aside>

    <!-- MAIN APPLICATION WRAPPER (RIGHT SIDE) -->
    <div id="app-body-wrapper" class="app-body-wrapper">

        <!-- Top Header / Navbar -->
        <header>
            <div class="header-left-flex">
                <button id="sidebar-toggle-btn" class="sidebar-toggle-btn" onclick="toggleMainSidebar()" title="Toggle Navigation Sidebar">
                    <span>☰</span>
                </button>
                <a href="{{ route('home') }}" class="logo">TanzaMart</a>
            </div>

            <nav>
                <a href="{{ route('cart.index') }}" class="header-cart-btn" title="{{ __('messages.nav_cart') }}">
                    <span>🛒</span>
                    @if($cartCount > 0)
                        <span style="background: #0b1329; color: #2563eb; padding: 1px 6px; border-radius: 10px; font-size: 11px; font-weight: bold;">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- Dropdown ya Lugha -->
                <select class="lang-select" onchange="location = this.value;">
                    <option value="?lang=en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                    <option value="?lang=sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>🇹🇿 SW</option>
                </select>
            </nav>
        </header>

        <!-- Flash Alerts -->
        @if(session('success') || session('error') || session('info'))
            <div class="flash-container">
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅ {{ session('success') }}</span>
                        <span onclick="this.parentElement.style.display='none'" style="cursor:pointer;">✕</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">
                        <span>❌ {{ session('error') }}</span>
                        <span onclick="this.parentElement.style.display='none'" style="cursor:pointer;">✕</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info">
                        <span>ℹ️ {{ session('info') }}</span>
                        <span onclick="this.parentElement.style.display='none'" style="cursor:pointer;">✕</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content Area -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer>
            <p>© 2026 TanzaMart. {{ __('messages.footer_rights') }}</p>
            <div class="footer-links">
                <a href="{{ route('home') }}">{{ __('messages.nav_home') }}</a> | 
                <a href="{{ route('about') }}" style="color: #2563eb; font-weight: 700;">ℹ️ {{ app()->getLocale() == 'sw' ? 'Kuhusu Sisi' : 'About Us' }}</a> | 
                <a href="{{ route('shop.products') }}">{{ __('messages.nav_products') }}</a> | 
                <a href="{{ route('order.track') }}" style="color: #2563eb; font-weight: bold;">{{ __('messages.nav_track') }} 📦</a> | 
                @auth
                    <a href="{{ route('order.my_orders') }}">{{ __('messages.nav_my_orders') }}</a> | 
                @endauth
                <a href="{{ route('cart.index') }}">{{ __('messages.nav_cart') }}</a>
            </div>
        </footer>

    </div>

    <!-- Chatbot Widget -->
    @include('partials.chatbot')

    <!-- Interactive Sidebar Script -->
    <script>
        function initSidebarState() {
            const isMobile = window.innerWidth <= 1024;
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (isMobile) {
                document.body.classList.remove('sidebar-closed');
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('active');
            } else {
                const savedState = localStorage.getItem('tanzamart_sidebar_state');
                if (savedState === 'closed') {
                    document.body.classList.add('sidebar-closed');
                } else {
                    document.body.classList.remove('sidebar-closed');
                }
            }
        }

        function toggleMainSidebar() {
            const isMobile = window.innerWidth <= 1024;
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (isMobile) {
                const isOpen = sidebar.classList.contains('mobile-open');
                if (isOpen) {
                    sidebar.classList.remove('mobile-open');
                    backdrop.classList.remove('active');
                } else {
                    sidebar.classList.add('mobile-open');
                    backdrop.classList.add('active');
                }
            } else {
                document.body.classList.toggle('sidebar-closed');
                const isClosed = document.body.classList.contains('sidebar-closed');
                localStorage.setItem('tanzamart_sidebar_state', isClosed ? 'closed' : 'open');
            }
        }

        window.addEventListener('resize', function() {
            initSidebarState();
        });

        function toggleCategoriesDropdown() {
            const btn = document.getElementById('categoryDropdownBtn');
            const content = document.getElementById('categoryDropdownContent');
            if (btn && content) {
                btn.classList.toggle('open');
                content.classList.toggle('open');
                const isOpen = content.classList.contains('open');
                localStorage.setItem('tanzamart_cat_dropdown', isOpen ? 'open' : 'closed');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initSidebarState();

            // Category Dropdown State Init
            const catPref = localStorage.getItem('tanzamart_cat_dropdown');
            const btn = document.getElementById('categoryDropdownBtn');
            const content = document.getElementById('categoryDropdownContent');
            const isFiltering = window.location.search.includes('category=');
            if (btn && content) {
                if (isFiltering || catPref === 'open' || catPref === null) {
                    btn.classList.add('open');
                    content.classList.add('open');
                } else {
                    btn.classList.remove('open');
                    content.classList.remove('open');
                }
            }
        });
    </script>

    @auth
        <!-- CHANGE PASSWORD MODAL -->
        @include('partials.change_password_modal')
    @endauth

    @stack('scripts')
</body>
</html>
