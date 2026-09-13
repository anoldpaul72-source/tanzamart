<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.nav_register') }} - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: white;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
            animation: fadeIn 0.4s ease-in-out;
            position: relative;
        }

        .header-actions {
            margin-bottom: 15px;
        }

        .back-link {
            text-decoration: none;
            color: #00bcd4;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #0097a7;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: bold;
        }

        p.subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .role-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 30px;
            margin-bottom: 20px;
        }

        .role-tab-btn {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            background: none;
            color: #555;
        }

        .role-tab-btn.active {
            background: #00bcd4;
            color: white;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
        }

        button[type="submit"] {
            width: 100%;
            padding: 13px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #0097a7;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="header-actions">
        <a href="{{ route('home') }}" class="back-link">{{ __('messages.back_home') }}</a>
    </div>

    <h2>TanzaMart</h2>
    <p class="subtitle">{{ app()->getLocale() === 'en' ? 'Create Your Account' : 'Tengeneza Akaunti Yako' }}</p>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <!-- Role Selector -->
        <div class="role-tabs">
            <button type="button" class="role-tab-btn {{ request('role') !== 'vendor' ? 'active' : '' }}" onclick="selectRole('user', this)">
                👤 {{ app()->getLocale() === 'en' ? 'Customer' : 'Mteja' }}
            </button>
            <button type="button" class="role-tab-btn {{ request('role') === 'vendor' ? 'active' : '' }}" onclick="selectRole('vendor', this)">
                🏪 {{ app()->getLocale() === 'en' ? 'Vendor' : 'Muuzaji' }}
            </button>
        </div>
        <input type="hidden" name="role" id="roleInput" value="{{ request('role') === 'vendor' ? 'vendor' : 'user' }}">

        <div class="input-group">
            <label for="name">{{ app()->getLocale() === 'en' ? 'Full Name' : 'Jina Kamili' }}</label>
            <input type="text" id="name" name="name" placeholder="{{ app()->getLocale() === 'en' ? 'e.g. Juma Khamis' : 'm.f. Juma Khamis' }}" value="{{ old('name') }}" required>
        </div>

        <div id="shopNameGroup" class="input-group" style="display: {{ request('role') === 'vendor' ? 'block' : 'none' }};">
            <label for="shop_name">{{ app()->getLocale() === 'en' ? 'Store Name' : 'Jina la Duka (Store Name)' }}</label>
            <input type="text" id="shop_name" name="shop_name" placeholder="{{ app()->getLocale() === 'en' ? 'e.g. Smart Electronics' : 'm.f. Smart Electronics' }}" value="{{ old('shop_name') }}">
        </div>

        <div class="input-group">
            <label for="email">{{ __('messages.email') }}</label>
            <input type="email" id="email" name="email" placeholder="{{ __('messages.enter_email') }}" value="{{ old('email') }}" required>
        </div>

        <div class="input-group">
            <label for="phone">{{ app()->getLocale() === 'en' ? 'Phone Number' : 'Namba ya Simu' }}</label>
            <input type="text" id="phone" name="phone" placeholder="07XXXXXXXX" value="{{ old('phone') }}" required>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="input-group">
                <label for="password">{{ __('messages.password') }}</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="input-group">
                <label for="password_confirmation">{{ app()->getLocale() === 'en' ? 'Confirm Password' : 'Thibitisha Nenosiri' }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit">{{ __('messages.nav_register') }}</button>
    </form>

    <div class="login-link">
        {{ app()->getLocale() === 'en' ? 'Already have an account?' : 'Umeshasajiliwa?' }} <a href="{{ route('login') }}">{{ __('messages.nav_login') }}</a>
    </div>
</div>

<script>
    function selectRole(role, btn) {
        document.getElementById('roleInput').value = role;
        document.querySelectorAll('.role-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('shopNameGroup').style.display = (role === 'vendor') ? 'block' : 'none';
    }
</script>

@include('partials.chatbot')

</body>
</html>
