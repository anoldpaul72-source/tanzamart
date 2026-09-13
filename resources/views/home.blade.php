@extends('layouts.app')

@section('title', 'TanzaMart - ' . __('messages.tagline'))

@push('styles')
<style>
    /* HERO SECTION */
    .hero {
        height: 500px;
        background: url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1400&auto=format&fit=crop') center/cover no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 20px;
    }

    .hero-content {
        background: rgba(0, 0, 0, 0.6);
        padding: 40px;
        border-radius: 10px;
        max-width: 680px;
        width: 100%;
    }

    .hero h1 {
        font-size: 50px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .hero p {
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: normal;
    }

    /* SECTION TITLES */
    .section-title {
        text-align: center;
        margin: 50px 0 20px;
        font-size: 32px;
        color: #333;
        font-weight: bold;
    }

    /* WHY CHOOSE US FEATURES */
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px 40px 60px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-box {
        background: white;
        padding: 35px 25px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
    }

    .feature-box:hover {
        transform: translateY(-5px);
    }

    .feature-box h3 {
        margin-bottom: 12px;
        color: #111;
        font-size: 20px;
        font-weight: bold;
    }

    .feature-box p {
        color: #555;
        font-size: 15px;
        line-height: 1.5;
    }

    /* POPULAR PRODUCTS */
    .products-preview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px 40px 60px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .product-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: scale(1.03);
    }

    .product-card img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background: #fafafa;
        padding: 10px;
    }

    .product-info {
        padding: 15px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }

    .product-info h3 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #111;
    }

    .product-info p {
        color: #00bcd4;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .product-info .btn {
        width: 100%;
    }

    /* CONTACT US SECTION */
    .contact-section {
        background: white;
        padding: 50px 40px;
        margin: 40px auto;
        max-width: 1100px;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .contact-card {
        background: #fdfdfd;
        padding: 25px;
        border-radius: 8px;
        text-align: center;
        border-top: 4px solid #00bcd4;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .contact-card h4 {
        font-size: 20px;
        color: #111;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .contact-card p {
        color: #555;
        font-size: 16px;
        line-height: 1.6;
    }

    .contact-card a {
        color: #00bcd4;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }

    .contact-card a:hover {
        color: #0097a7;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 35px;
        }
        .hero p {
            font-size: 17px;
        }
        .hero-content {
            padding: 25px 20px;
        }
        .features {
            padding: 20px 20px 40px;
        }
        .products-preview {
            padding: 20px 20px 40px;
        }
        .contact-section {
            margin: 20px 15px;
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>{{ __('messages.hero_title') }}</h1>
            <p>{{ __('messages.hero_subtitle') }}</p>
            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('shop.products') }}" class="btn">{{ __('messages.hero_btn') }}</a>
                <a href="{{ route('about') }}" class="btn" style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.4); backdrop-filter: blur(4px);">ℹ️ {{ app()->getLocale() == 'sw' ? 'Kuhusu TanzaMart' : 'About TanzaMart' }}</a>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <h2 class="section-title">{{ __('messages.why_us_title') }}</h2>

    <section class="features">
        <div class="feature-box">
            <h3>{{ __('messages.feat_delivery_title') }}</h3>
            <p>{{ __('messages.feat_delivery_desc') }}</p>
        </div>

        <div class="feature-box">
            <h3>{{ __('messages.feat_price_title') }}</h3>
            <p>{{ __('messages.feat_price_desc') }}</p>
        </div>

        <div class="feature-box">
            <h3>{{ __('messages.feat_secure_title') }}</h3>
            <p>{{ __('messages.feat_secure_desc') }}</p>
        </div>
    </section>

    <!-- POPULAR PRODUCTS -->
    <h2 class="section-title">{{ __('messages.popular_title') }}</h2>

    <section class="products-preview">
        @forelse($featuredProducts as $product)
            <div class="product-card">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/logo.jpg') }}'">
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ __('messages.price_tsh') }} {{ number_format($product->price) }}</p>
                    <a href="{{ route('shop.product.details', $product->id) }}" class="btn">{{ __('messages.view_product') }}</a>
                </div>
            </div>
        @empty
            <div class="product-card">
                <img src="{{ asset('images/iphone.jpg') }}" alt="iPhone">
                <div class="product-info">
                    <h3>Apple iPhone 17 Pro</h3>
                    <p>{{ __('messages.price_tsh') }} 3,800,000</p>
                    <a href="{{ route('shop.products') }}" class="btn">{{ __('messages.view_product') }}</a>
                </div>
            </div>
            <div class="product-card">
                <img src="{{ asset('images/samsung.jpg') }}" alt="Samsung">
                <div class="product-info">
                    <h3>Samsung Galaxy S24</h3>
                    <p>{{ __('messages.price_tsh') }} 3,200,000</p>
                    <a href="{{ route('shop.products') }}" class="btn">{{ __('messages.view_product') }}</a>
                </div>
            </div>
            <div class="product-card">
                <img src="{{ asset('images/pixel.jpg') }}" alt="Google Pixel">
                <div class="product-info">
                    <h3>Google Pixel 8 Pro</h3>
                    <p>{{ __('messages.price_tsh') }} 2,100,000</p>
                    <a href="{{ route('shop.products') }}" class="btn">{{ __('messages.view_product') }}</a>
                </div>
            </div>
            <div class="product-card">
                <img src="{{ asset('images/sneaker.jpg') }}" alt="Sneakers">
                <div class="product-info">
                    <h3>Air Jordan Sneakers</h3>
                    <p>{{ __('messages.price_tsh') }} 180,000</p>
                    <a href="{{ route('shop.products') }}" class="btn">{{ __('messages.view_product') }}</a>
                </div>
            </div>
        @endforelse
    </section>

    <!-- CONTACT INFORMATION SECTION -->
    <section class="contact-section">
        <h2 class="section-title" style="margin-top: 0;">{{ __('messages.contact_title') }}</h2>
        <p style="text-align: center; color: #666; margin-bottom: 10px;">{{ __('messages.contact_desc') }}</p>
        
        <div class="contact-grid">
            <div class="contact-card">
                <h4>{{ __('messages.phone_support') }}</h4>
                <p>{{ __('messages.phone_desc') }}</p>
                <p style="margin-top: 5px;">
                    <a href="tel:+255621530804">+255 621 530 804</a><br>
                    <a href="tel:+255657276380">+255 657 276 380</a><br>
                    <a href="tel:+255616838785">+255 616 838 785</a><br>
                    <a href="tel:+255626979764">+255 626 979 764</a><br>
                    <a href="tel:+255773068054">+255 773 068 054</a>
                </p>
            </div>

            <div class="contact-card">
                <h4>{{ __('messages.email_support') }}</h4>
                <p>{{ __('messages.email_desc') }}</p>
                <p style="margin-top: 12px;">
                    <a href="mailto:sylvesterarnold72@gmail.com">sylvesterarnold72@gmail.com</a>
                </p>
            </div>

            <div class="contact-card">
                <h4>{{ __('messages.location_title') }}</h4>
                <p>{{ __('messages.location_desc') }}</p>
                <p style="margin-top: 12px; font-weight: bold; color: #333;">
                    {{ __('messages.location_val') }}
                </p>
            </div>
        </div>
    </section>

@endsection
