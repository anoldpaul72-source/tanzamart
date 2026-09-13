<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.cust_chk_title') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .checkout-container { max-width: 650px; margin: 20px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }
        .summary { background: #eef9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #00bcd4; }
        .summary h3 { margin: 0 0 5px 0; color: #008ba3; font-size: 18px; }
        .payment-methods-box { background: #fafafa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px; margin-bottom: 25px; }
        .payment-methods-box h4 { margin-top: 0; color: #222; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        .payment-option { background: #fff; border: 1px solid #eee; padding: 12px; border-radius: 5px; margin-bottom: 12px; }
        .payment-option strong { color: #00bcd4; font-size: 15px; display: block; margin-bottom: 5px; }
        .payment-option p { margin: 3px 0; font-size: 14px; color: #444; }
        .hint-text { font-size: 13px; color: #666; margin-top: 6px; background: #fff8e1; padding: 8px; border-radius: 4px; border-left: 3px solid #ffb300; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; font-size: 14px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; font-family: inherit; }
        .btn-submit { background-color: #00bcd4; color: white; border: none; padding: 14px 20px; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s; }
        .btn-submit:hover { background-color: #008ba3; }
        .alert { padding: 15px; margin-bottom: 15px; border-radius: 6px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .notification-buttons { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .btn-wa { background-color: #25D366; color: white; text-align: center; padding: 14px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 16px; display: block; }
        .btn-wa:hover { background-color: #1eb954; }
        .btn-sms { background-color: #007bff; color: white; text-align: center; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 15px; display: block; }
        .btn-sms:hover { background-color: #0056b3; }
        .btn-orders { display: inline-block; padding: 10px 18px; background: #333; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; }
        .btn-orders:hover { background: #111; }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>{{ __('messages.cust_chk_heading') }}</h2>

    @if($success)
        <div class="alert alert-success">
            <h3 style="margin-top:0;">{{ sprintf(__('messages.cust_chk_success_title'), $order_id) }}</h3>
            <p>{{ __('messages.cust_chk_success_msg') }}</p>

            <div class="notification-buttons">
                <a href="{{ $wa_url }}" target="_blank" class="btn-wa">{{ __('messages.cust_chk_btn_wa') }}</a>
                <a href="{{ $sms_url }}" class="btn-sms">{{ __('messages.cust_chk_btn_sms') }}</a>
            </div>

            <br>
            <a href="{{ route('order.my_orders') }}" class="btn-orders">{{ __('messages.cust_chk_btn_orders') }}</a>
            <a href="{{ route('home') }}" style="margin-left:15px; color:#333; text-decoration:none;">{{ __('messages.cust_chk_btn_home') }}</a>
        </div>
    @else

        @if(!empty($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        <div class="summary">
            <h3>{{ __('messages.cust_chk_total_amount') }} TSh {{ number_format($total_amount, 2) }}</h3>
            <p>{{ __('messages.cust_chk_subtext') }}</p>
        </div>

        <div class="payment-methods-box">
            <h4>📁 {{ __('messages.cust_chk_instructions') }}</h4>

            <div class="payment-option">
                <strong>{{ __('messages.cust_chk_mobile_title') }}</strong>
                <p>{{ __('messages.cust_chk_mobile_num') }} <strong>5521400</strong></p>
                <p>{{ __('messages.cust_chk_mobile_name') }} <strong>TanzaMart Ltd</strong></p>
                <p>{{ __('messages.cust_chk_mobile_nets') }}</p>
            </div>

            <div class="payment-option">
                <strong>{{ __('messages.cust_chk_bank_title') }}</strong>
                <p>{{ __('messages.cust_chk_bank_name') }} <strong>CRDB Bank</strong></p>
                <p>{{ __('messages.cust_chk_acc_name') }} <strong>TanzaMart Limited</strong></p>
                <p>{{ __('messages.cust_chk_acc_no') }} <strong>0152839120300</strong></p>
            </div>

            <div class="hint-text">
                💡 <strong>Transaction ID:</strong> {{ app()->getLocale() === 'sw' ? 'Hizi ni namba au herufi unazopokea kwenye SMS baada ya kutuma fedha.' : 'These are the numbers/letters you receive via SMS after sending money.' }}
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="form-group">
                <label for="name">{{ __('messages.cust_chk_lbl_name') }}</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $user->name ?? '') }}" placeholder="{{ __('messages.cust_chk_ph_name') }}">
            </div>

            <div class="form-group">
                <label for="phone">{{ __('messages.cust_chk_lbl_phone') }}</label>
                <input type="text" id="phone" name="phone" required value="{{ old('phone', $user->phone ?? '') }}" placeholder="{{ __('messages.cust_chk_ph_phone') }}">
            </div>

            <div class="form-group">
                <label for="address">{{ __('messages.cust_chk_lbl_address') }}</label>
                <textarea id="address" name="address" rows="3" required placeholder="{{ __('messages.cust_chk_ph_address') }}">{{ old('address', $user->address ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="city">{{ __('messages.cust_chk_lbl_city') }}</label>
                <input type="text" id="city" name="city" required value="{{ old('city', $user->city ?? '') }}" placeholder="{{ __('messages.cust_chk_ph_city') }}">
            </div>

            <div class="form-group">
                <label for="transaction_id">{{ __('messages.cust_chk_lbl_txn') }}</label>
                <input type="text" id="transaction_id" name="transaction_id" required value="{{ old('transaction_id') }}" placeholder="{{ __('messages.cust_chk_ph_txn') }}">
            </div>

            <button type="submit" class="btn-submit">{{ __('messages.cust_chk_btn_submit') }}</button>
        </form>

    @endif
</div>

@include('partials.chatbot')

</body>
</html>
