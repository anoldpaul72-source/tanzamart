<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TanzaMart - ' . __('messages.tagline'))</title>

    <meta name="theme-color" content="#00bcd4">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #222;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* HEADER */
        header {
            background: #111;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #00bcd4;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
            transition: 0.3s;
            font-weight: 500;
        }

        nav a:hover {
            color: #00bcd4;
        }

        .lang-select {
            margin-left: 20px;
            padding: 6px 10px;
            background: #222;
            color: #00bcd4;
            border: 1px solid #00bcd4;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            outline: none;
            transition: 0.3s;
        }

        .lang-select:hover {
            background: #00bcd4;
            color: #111;
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
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        /* Generic Button */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #00bcd4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background: #0097a7;
        }

        /* FOOTER */
        footer {
            background: #111;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #00bcd4;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
                padding: 15px 20px;
            }
            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }
            nav a {
                margin-left: 0;
            }
            .lang-select {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header / Navbar matching original screenshot -->
    <header>
        <a href="{{ route('home') }}" class="logo">TanzaMart</a>
        <nav>
            <a href="{{ route('home') }}">{{ __('messages.nav_home') }}</a>
            <a href="{{ route('shop.products') }}">{{ __('messages.nav_products') }}</a>
            <a href="{{ route('order.track') }}">{{ __('messages.nav_track') }}</a>
            <a href="{{ route('cart.index') }}">{{ __('messages.nav_cart') }}</a>

            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" style="color: #ffc107; font-weight: bold;">⚡ {{ __('messages.nav_admin') }}</a>
                @elseif(Auth::user()->isVendor())
                    <a href="{{ route('vendor.dashboard') }}" style="color: #28a745; font-weight: bold;">🏪 {{ __('messages.nav_vendor') }}</a>
                @endif
                <a href="{{ route('order.my_orders') }}" style="color: #00bcd4; font-weight: bold;">{{ __('messages.nav_my_orders') }}</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline; margin: 0 0 0 20px;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:white; font-size:16px; cursor:pointer; font-family:inherit; transition:0.3s;" onmouseover="this.style.color='#00bcd4'" onmouseout="this.style.color='white'">{{ __('messages.nav_logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}">{{ __('messages.nav_login') }}</a>
                <a href="{{ route('register') }}">{{ __('messages.nav_register') }}</a>
            @endauth

            <!-- Dropdown ya Kuchagua Lugha -->
            <select class="lang-select" onchange="location = this.value;">
                <option value="?lang=en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                <option value="?lang=sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
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

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer matching original screenshot -->
    <footer>
        <p>© 2026 TanzaMart. {{ __('messages.footer_rights') }}</p>
        <div class="footer-links">
            <a href="{{ route('home') }}">{{ __('messages.nav_home') }}</a> | 
            <a href="{{ route('shop.products') }}">{{ __('messages.nav_products') }}</a> | 
            <a href="{{ route('order.track') }}" style="color: #00bcd4; font-weight: bold;">{{ __('messages.nav_track') }} 📦</a> | 
            @auth
                <a href="{{ route('order.my_orders') }}">{{ __('messages.nav_my_orders') }}</a> | 
            @endauth
            <a href="{{ route('cart.index') }}">{{ __('messages.nav_cart') }}</a>
        </div>
    </footer>

    <!-- Chatbot Widget -->
    @include('partials.chatbot')

    @stack('scripts')
</body>
</html>
