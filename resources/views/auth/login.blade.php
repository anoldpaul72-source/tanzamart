<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.login') }} - TanzaMart</title>
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

        .login-container {
            background: white;
            padding: 40px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 410px;
            animation: fadeIn 0.4s ease-in-out;
            position: relative;
        }

        .header-actions {
            margin-bottom: 15px;
        }

        .back-link {
            text-decoration: none;
            color: #2563eb;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #1d4ed8;
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
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .input-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #2563eb;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            margin: 0;
            width: auto;
            outline: none;
        }

        .forgot-link-container {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot-link-container a {
            color: #2563eb;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link-container a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        button[type="submit"] {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background: #1d4ed8;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="header-actions">
        <a href="{{ route('home') }}" class="back-link">{{ __('messages.back_home') }}</a>
    </div>

    <h2>TanzaMart</h2>
    <p class="subtitle">{{ __('messages.enter_account') }}</p>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="input-group">
            <label for="email">{{ __('messages.email') }}</label>
            <input type="email" id="email" name="email" placeholder="{{ __('messages.enter_email') }}" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="input-group">
            <label for="password">{{ __('messages.password') }}</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="{{ __('messages.enter_password') }}" required>
                <button type="button" id="toggleBtn" class="toggle-password" title="Show/Hide Password">👁️</button>
            </div>
        </div>

        <div class="forgot-link-container">
            <a href="{{ route('support.index') }}">{{ __('messages.forgot_password') }}</a>
        </div>

        <button type="submit" name="login">{{ __('messages.login') }}</button>
    </form>

    <div class="register-link">
        {{ __('messages.no_account') }} <a href="{{ route('register') }}">{{ __('messages.register_here') }}</a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggleBtn');

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = '👁️';
        }
    });
</script>

@include('partials.chatbot')

</body>
</html>
