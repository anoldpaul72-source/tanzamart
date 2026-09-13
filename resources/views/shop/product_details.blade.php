<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - TanzaMart</title>
    <meta name="theme-color" content="#00bcd4">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; margin: 0; padding: 25px 20px; min-height: 100vh; }
        .back-btn-container { max-width: 1000px; margin: 0 auto 15px auto; }
        .back-btn { display: inline-block; color: #00bcd4; text-decoration: none; font-weight: bold; font-size: 15px; }
        .back-btn:hover { text-decoration: underline; }
        
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white; 
            padding: 35px 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.06); 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); 
            gap: 40px; 
        }
        .gallery { text-align: center; }
        
        .main-img { 
            width: 100%; 
            max-height: 420px; 
            height: 380px; 
            object-fit: cover; 
            background: #fafafa; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
        }
        
        .color-instruction { 
            font-size: 13.5px; 
            color: #666; 
            font-weight: bold; 
            margin-top: 18px; 
            margin-bottom: 8px; 
            display: block; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .color-instruction span { color: #00bcd4; }

        .thumbnails { 
            display: flex; 
            gap: 10px; 
            margin-top: 10px; 
            justify-content: center; 
            flex-wrap: wrap; 
        }
        
        .thumb { 
            width: 70px; 
            height: 70px; 
            object-fit: cover; 
            background: #fafafa; 
            border-radius: 6px; 
            cursor: pointer; 
            border: 2px solid #ddd; 
            transition: 0.2s; 
        }
        .thumb:hover, .thumb.active { 
            border-color: #00bcd4; 
            transform: scale(1.05); 
        }
        
        .details-info h2 { 
            margin-top: 0; 
            font-size: 30px; 
            color: #111; 
            font-weight: 800; 
            margin-bottom: 12px; 
        }
        
        .category-row {
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
            font-weight: 600;
        }
        .category-badge { 
            display: inline-block; 
            padding: 5px 12px; 
            border-radius: 4px; 
            font-weight: bold; 
            font-size: 13px; 
            background: #fff3e0; 
            color: #e65100; 
            border: 1px solid #ffe0b2; 
            text-decoration: none; 
            margin-left: 6px;
        }

        .price { 
            font-size: 26px; 
            color: #00bcd4; 
            font-weight: bold; 
            margin: 15px 0; 
        }
        
        .badges-wrapper { 
            display: flex; 
            gap: 10px; 
            flex-wrap: wrap; 
            margin-bottom: 22px; 
        }
        .badge { 
            display: inline-block; 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-weight: bold; 
            font-size: 13px; 
        }
        .stock-badge { 
            background: #e8f5e9; 
            color: #2e7d32; 
            border: 1px solid #c8e6c9; 
        }
        .out-of-stock { 
            background: #ffebee; 
            color: #c62828; 
            border: 1px solid #ffcdd2; 
        }
        .views-badge { 
            background: #e0f7fa; 
            color: #00838f; 
            border: 1px solid #b2ebf2; 
        }

        .details-heading {
            font-size: 15px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .description-text { 
            font-size: 15px; 
            color: #555; 
            line-height: 1.6; 
            margin-bottom: 30px; 
            white-space: pre-line;
        }
        
        .cart-btn { 
            background: #111; 
            color: white; 
            padding: 15px 30px; 
            border: none; 
            font-size: 16px; 
            font-weight: bold; 
            border-radius: 6px; 
            cursor: pointer; 
            transition: 0.3s; 
            width: 100%; 
            text-align: center; 
        }
        .cart-btn:hover { background: #00bcd4; }
        .cart-btn:disabled { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>

<div class="back-btn-container">
    <a href="{{ route('shop.products') }}" class="back-btn">{{ __('messages.back_to_products') }}</a>
</div>

<div class="container">
    <div class="gallery">
        @php
            $imagesList = $product->images_list;
            $mainImg = $imagesList[0] ?? $product->image_url;
        @endphp
        
        <img id="featured-image" src="{{ $mainImg }}" alt="{{ $product->name }}" class="main-img" onerror="this.src='{{ asset('images/logo.jpg') }}'">
        
        <span class="color-instruction">
            🎨 <span>{{ __('messages.choose_color') }}</span> {{ __('messages.then_add_to_cart') }}
        </span>
        
        <div class="thumbnails">
            @foreach($imagesList as $key => $imgUrl)
                <img src="{{ $imgUrl }}" class="thumb {{ $key === 0 ? 'active' : '' }}" onclick="changeImage(this)" alt="Color option {{ $key + 1 }}">
            @endforeach
        </div>
    </div>

    <div class="details-info">
        <h2>{{ $product->name }}</h2>
        
        @if($product->category)
            <div class="category-row">
                {{ __('messages.category_lbl') }}
                <a href="{{ route('shop.products', ['category' => $product->category->id]) }}" class="category-badge">
                    📁 {{ $product->category->name }}
                </a>
            </div>
        @endif

        <div class="price">Tsh {{ number_format($product->price) }}</div>
        
        <div class="badges-wrapper">
            @if($product->stock > 0)
                <div class="badge stock-badge">
                    📦 {{ __('messages.items_left_stock') }} <strong>{{ $product->stock }}</strong> {{ __('messages.available_items') }}
                </div>
            @else
                <div class="badge out-of-stock">
                    ❌ {{ __('messages.out_of_stock') }}
                </div>
            @endif

            <div class="badge views-badge">
                👁️ {{ sprintf(__('messages.viewed_times'), $product->views ?? 2) }}
            </div>
        </div>

        <div class="details-heading">{{ __('messages.product_details_lbl') }}</div>
        <div class="description-text">{{ $product->details ?? "Condition 10/10\nSize 40-45" }}</div>

        @if($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="selected_image" id="selected_image_input" value="{{ $mainImg }}">
                <button type="submit" class="cart-btn">
                    🛒 {{ __('messages.add_to_cart') }}
                </button>
            </form>
        @else
            <button type="button" class="cart-btn" disabled>
                {{ __('messages.out_of_stock') }}
            </button>
        @endif
    </div>
</div>

<script>
function changeImage(element) {
    var mainImg = document.getElementById('featured-image');
    var inputImg = document.getElementById('selected_image_input');
    
    mainImg.src = element.src;
    if (inputImg) {
        inputImg.value = element.src;
    }
    
    var thumbs = document.querySelectorAll('.thumb');
    thumbs.forEach(function(thumb) {
        thumb.classList.remove('active');
    });
    
    element.classList.add('active');
}
</script>

@include('partials.chatbot')

</body>
</html>
