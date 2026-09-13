<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.sub_title') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; padding: 40px 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: white; padding: 40px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); width: 100%; max-width: 700px; text-align: center; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #00bcd4; font-size: 14px; font-weight: bold; float: left; }
        .back-link:hover { color: #0097a7; }
        h2 { color: #333; margin-bottom: 10px; clear: both; font-size: 22px; font-weight: bold; }
        p.subtitle { color: #777; font-size: 14px; margin-bottom: 30px; }
        .plans-grid { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }
        .plan-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px 20px; flex: 1; min-width: 190px; text-align: center; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; }
        .plan-card:hover { border-color: #00bcd4; box-shadow: 0 5px 15px rgba(0,188,212,0.15); transform: translateY(-3px); }
        .plan-card h3 { color: #444; margin-bottom: 15px; font-size: 16px; font-weight: bold; }
        .plan-card p { font-size: 17px; font-weight: bold; color: #00bcd4; margin-bottom: 20px; }
        .btn-choose { background: #00bcd4; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; text-decoration: none; display: block; width: 100%; font-size: 14px; transition: background 0.3s; }
        .btn-choose:hover { background: #0097a7; }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('vendor.dashboard') }}" class="back-link">{{ __('messages.sub_back_dashboard') }}</a>
    
    <h2>{{ __('messages.sub_heading') }}</h2>
    <p class="subtitle">{{ __('messages.sub_subtext') }}</p>

    <div class="plans-grid">
        <!-- Weekly Plan -->
        <div class="plan-card">
            <div>
                <h3>{{ __('messages.sub_weekly') }}</h3>
                <p>Tsh 5,000/=</p>
            </div>
            <a href="{{ route('vendor.checkout') }}?plan={{ urlencode(__('messages.sub_weekly')) }}&amount=5000" class="btn-choose">
                {{ __('messages.sub_btn_choose') }}
            </a>
        </div>

        <!-- Monthly Plan -->
        <div class="plan-card">
            <div>
                <h3>{{ __('messages.sub_monthly') }}</h3>
                <p>Tsh 15,000/=</p>
            </div>
            <a href="{{ route('vendor.checkout') }}?plan={{ urlencode(__('messages.sub_monthly')) }}&amount=15000" class="btn-choose">
                {{ __('messages.sub_btn_choose') }}
            </a>
        </div>

        <!-- Yearly Plan -->
        <div class="plan-card">
            <div>
                <h3>{{ __('messages.sub_yearly') }}</h3>
                <p>Tsh 150,000/=</p>
            </div>
            <a href="{{ route('vendor.checkout') }}?plan={{ urlencode(__('messages.sub_yearly')) }}&amount=150000" class="btn-choose">
                {{ __('messages.sub_btn_choose') }}
            </a>
        </div>
    </div>
</div>

</body>
</html>
