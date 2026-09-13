<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.products_header_title') }} - TanzaMart</title>
    <meta name="theme-color" content="#00bcd4">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto 20px auto;
        }

        .home-btn {
            text-decoration: none;
            color: #00bcd4;
            font-weight: bold;
            font-size: 16px;
            padding: 8px 15px;
            border: 2px solid #00bcd4;
            border-radius: 4px;
            transition: 0.3s;
            background: white;
        }

        .home-btn:hover {
            background: #00bcd4;
            color: white;
        }

        h2 {
            margin: 0;
            flex-grow: 1;
            text-align: center;
            font-size: 28px;
            color: #111;
            font-weight: bold;
        }

        form.search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        input.search-input {
            padding: 10px 14px;
            width: 260px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            font-size: 14px;
        }

        input.search-input:focus {
            border-color: #00bcd4;
        }

        button.search-btn {
            padding: 10px 18px;
            background: #00bcd4;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        button.search-btn:hover {
            background: #0097a7;
        }

        .category-filter {
            max-width: 1200px;
            margin: 0 auto 25px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .category-btn {
            text-decoration: none;
            padding: 8px 18px;
            background-color: #ffffff;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .category-btn:hover, .category-btn.active {
            background: #00bcd4;
            color: white;
            border-color: #00bcd4;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .product:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.12);
        }

        .product img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: #fafafa;
            border-radius: 10px;
        }

        .verified-badge {
            position: absolute;
            top: 22px;
            left: 22px;
            background: #00bcd4;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            z-index: 2;
        }

        .product h3 {
            margin: 12px 0 6px;
            font-size: 16px;
        }

        .product h3 a {
            color: #0033cc;
            text-decoration: underline;
            font-weight: bold;
            transition: 0.2s;
        }

        .product h3 a:hover {
            color: #00bcd4;
        }

        .price-tag {
            color: #00bcd4;
            font-weight: bold;
            font-size: 18px;
            margin: 5px 0 10px;
        }

        .btn-group-card {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .cart-btn {
            flex: 1;
            padding: 10px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 13px;
            text-align: center;
            transition: 0.3s;
        }

        .cart-btn:hover {
            background: #00bcd4;
        }

        .pagination-container {
            max-width: 1200px;
            margin: 30px auto 10px auto;
            display: flex;
            justify-content: center;
        }

        .pagination-container .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
        }

        .pagination-container .pagination li a, 
        .pagination-container .pagination li span {
            display: block;
            padding: 8px 14px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .pagination-container .pagination li.active span {
            background: #00bcd4;
            color: white;
            border-color: #00bcd4;
        }

        @media(max-width: 768px) {
            .products-header {
                flex-direction: column;
                gap: 10px;
            }
            .home-btn {
                align-self: flex-start;
            }
            input.search-input {
                width: 180px;
            }
        }
    </style>
</head>
<body>

<div class="products-header">
    <a href="{{ route('home') }}" class="home-btn">{{ __('messages.back_home') }}</a>
    <h2>{{ __('messages.products_header_title') }}</h2>
    <div style="width: 130px;"></div>
</div>

<form method="GET" action="{{ route('shop.products') }}" class="search-form">
    <input type="text" name="search" class="search-input" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
    <button type="submit" class="search-btn">{{ __('messages.search_btn') }}</button>
</form>

<!-- CATEGORY FILTER CHIPS -->
<div class="category-filter">
    <a href="{{ route('shop.products') }}" class="category-btn {{ (!request('category') && !request('search')) ? 'active' : '' }}">
        {{ __('messages.all_cat') }}
    </a>
    
    @foreach($categories as $cat)
        <a href="{{ route('shop.products', ['category' => $cat->id]) }}" class="category-btn {{ request('category') == $cat->id ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

<!-- PRODUCTS GRID -->
<div class="products">
    @forelse($products as $product)
        <div class="product">
            <div class="verified-badge">✔ {{ __('messages.verified_badge') }}</div>
            <a href="{{ route('shop.product.details', $product->id) }}">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/logo.jpg') }}'">
            </a>
            <h3>
                <a href="{{ route('shop.product.details', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>
            <p class="price-tag">Tsh {{ number_format($product->price) }}</p>
            <div class="btn-group-card">
                <a class="cart-btn" href="{{ route('shop.product.details', $product->id) }}">{{ __('messages.btn_details') }}</a>
            </div>
        </div>
    @empty
        <p style="text-align:center; grid-column: 1/-1; color: #777; font-size: 16px; padding: 40px;">
            {{ __('messages.no_products') }}
        </p>
    @endforelse
</div>

@if($products->hasPages())
    <div class="pagination-container">
        {{ $products->links() }}
    </div>
@endif

@include('partials.chatbot')

</body>
</html>
