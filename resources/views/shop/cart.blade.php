<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.cart_main_title') }} - TanzaMart</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; padding: 40px 20px; color: #333; }
        .cart-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .back-link { text-decoration: none; color: #2563eb; font-size: 14px; font-weight: bold; }
        h2 { text-align: center; margin-bottom: 30px; color: #222; font-size: 28px; }
        h2::after { content: ''; display: block; width: 50px; height: 3px; background: #2563eb; margin: 8px auto 0; border-radius: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .cart-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 15px; border: 1px solid #ddd; }
        .product-info { display: flex; align-items: center; }
        .price { font-weight: bold; color: #444; }
        .btn-remove { color: #e53935; text-decoration: none; font-size: 14px; }
        .cart-summary { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 2px solid #eee; }
        .total-price { font-size: 22px; font-weight: bold; color: #2563eb; }
        .cart-actions { display: flex; justify-content: space-between; margin-top: 30px; gap: 10px; flex-wrap: wrap; }
        .btn-continue { color: #555; text-decoration: none; padding: 12px 20px; border: 1px solid #ddd; border-radius: 6px; }
        .btn-checkout { background: #2563eb; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>

<div class="cart-container">
    <div class="header-actions">
        <a href="{{ route('home') }}" class="back-link">{{ __('messages.cart_back_home') }}</a>
    </div>

    <h2>{{ __('messages.cart_main_title') }}</h2>

    @if(empty($cart))
        <div class="empty-cart" style="text-align: center; padding: 40px 0;">
            <p>{{ __('messages.cart_is_empty') }}</p>
            <br>
            <a href="{{ route('shop.products') }}" style="color: #2563eb; text-decoration: none; font-weight: bold;">{{ __('messages.cart_go_shop') }}</a>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.cart_th_product') }}</th>
                    <th>{{ __('messages.cart_th_price') }}</th>
                    <th>{{ __('messages.cart_th_qty') }}</th>
                    <th>{{ __('messages.cart_th_subtotal') }}</th>
                    <th style="text-align: right;">{{ __('messages.cart_th_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $calculatedTotal = 0; @endphp
                @foreach($cart as $cart_key => $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $calculatedTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="{{ $item['image'] ?? asset('images/logo.jpg') }}" alt="{{ $item['name'] }}" class="cart-thumb" onerror="this.src='{{ asset('images/logo.jpg') }}'">
                                <span>{{ $item['name'] }}</span>
                            </div>
                        </td>
                        <td class="price">Tsh {{ number_format($item['price']) }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td class="price">Tsh {{ number_format($subtotal) }}</td>
                        <td style="text-align: right;">
                            <a class="btn-remove" href="{{ route('cart.index', ['remove' => $cart_key]) }}">{{ __('messages.cart_btn_remove') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-summary">
            <span>{{ __('messages.cart_lbl_total') }}</span>
            <span class="total-price">Tsh {{ number_format($calculatedTotal) }}</span>
        </div>

        <div class="cart-actions">
            <a href="{{ route('shop.products') }}" class="btn-continue">{{ __('messages.cart_btn_continue') }}</a>
            <a href="{{ route('checkout.index') }}" class="btn-checkout">{{ __('messages.cart_btn_checkout') }}</a>
        </div>
    @endif
</div>

@include('partials.chatbot')

</body>
</html>
