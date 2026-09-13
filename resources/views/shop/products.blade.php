@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' - ' : '') . __('messages.products_header_title') . ' - TanzaMart')

@push('styles')
<style>
    .shop-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        width: 100%;
    }

    /* BREADCRUMBS & HERO */
    .shop-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 30px 32px;
        color: #ffffff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    }

    .shop-hero::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .shop-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .shop-breadcrumbs a {
        color: #94a3b8;
        text-decoration: none;
        transition: color 0.2s;
    }

    .shop-breadcrumbs a:hover {
        color: #38bdf8;
    }

    .shop-breadcrumbs .sep {
        color: #475569;
        font-size: 11px;
    }

    .shop-breadcrumbs .current {
        color: #ffffff;
        font-weight: 600;
    }

    .shop-hero-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
    }

    .shop-hero-title {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 6px;
    }

    .shop-hero-subtitle {
        color: #94a3b8;
        font-size: 14px;
        margin: 0;
    }

    .shop-hero-count {
        background: rgba(37, 99, 235, 0.25);
        border: 1px solid rgba(37, 99, 235, 0.5);
        color: #60a5fa;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* HORIZONTAL SCROLL CATEGORIES BAR */
    .category-scroll-wrapper {
        position: relative;
        margin-bottom: 20px;
    }

    .category-scroll-track {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 4px 2px 12px;
        scrollbar-width: thin;
        scrollbar-color: rgba(37, 99, 235, 0.3) transparent;
        -webkit-overflow-scrolling: touch;
    }

    .category-scroll-track::-webkit-scrollbar {
        height: 5px;
    }
    .category-scroll-track::-webkit-scrollbar-thumb {
        background: rgba(37, 99, 235, 0.3);
        border-radius: 999px;
    }

    .cat-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #ffffff;
        color: #334155;
        border: 1.5px solid #e2e8f0;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        flex-shrink: 0;
    }

    .cat-chip:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
        transform: translateY(-1px);
    }

    .cat-chip.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .cat-chip .chip-count {
        background: rgba(0, 0, 0, 0.07);
        color: inherit;
        font-size: 11px;
        padding: 1px 7px;
        border-radius: 999px;
        font-weight: 700;
    }

    .cat-chip.active .chip-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* SEARCH & FILTER TOOLBAR */
    .shop-toolbar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .toolbar-search-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        max-width: 650px;
        flex-wrap: wrap;
    }

    .toolbar-cat-select {
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
        min-width: 180px;
    }

    .toolbar-cat-select:focus {
        border-color: #2563eb;
    }

    .toolbar-search-input-wrap {
        display: flex;
        flex: 1;
        min-width: 220px;
    }

    .toolbar-search-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px 0 0 8px;
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.2s;
    }

    .toolbar-search-input:focus {
        border-color: #2563eb;
    }

    .toolbar-search-btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 0 8px 8px 0;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .toolbar-search-btn:hover {
        background: #1d4ed8;
    }

    .toolbar-sort-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .toolbar-sort-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        white-space: nowrap;
    }

    .toolbar-sort-select {
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        background: #ffffff;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .toolbar-sort-select:focus {
        border-color: #2563eb;
    }

    /* PRODUCTS GRID */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 22px;
        margin-bottom: 40px;
    }

    .product-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .product-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .product-card-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #2563eb;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 999px;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .product-image-container {
        position: relative;
        width: 100%;
        height: 230px;
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
    }

    .product-image-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-card-modern:hover .product-image-container img {
        transform: scale(1.06);
    }

    .product-card-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }

    .product-cat-tag {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
        text-decoration: none;
    }

    .product-cat-tag:hover {
        color: #2563eb;
    }

    .product-title-link {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        text-decoration: none;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }

    .product-title-link:hover {
        color: #2563eb;
    }

    .product-vendor-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 12px;
    }

    .product-vendor-row span {
        font-weight: 600;
        color: #334155;
    }

    .product-price-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        margin-bottom: 14px;
    }

    .product-price-val {
        font-size: 19px;
        font-weight: 800;
        color: #2563eb;
        letter-spacing: -0.5px;
    }

    .product-stock-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
    }

    .product-stock-badge.in-stock {
        background: #dcfce7;
        color: #15803d;
    }

    .product-stock-badge.low-stock {
        background: #fef3c7;
        color: #b45309;
    }

    .product-card-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
    }

    .btn-add-cart {
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    .btn-add-cart:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .btn-view-details {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-view-details:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* EMPTY STATE */
    .empty-state-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 60px 24px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        display: inline-block;
    }

    .empty-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .empty-desc {
        color: #64748b;
        font-size: 14.5px;
        max-width: 480px;
        margin: 0 auto 24px;
        line-height: 1.5;
    }

    .btn-reset-filter {
        background: #2563eb;
        color: white;
        padding: 11px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .btn-reset-filter:hover {
        background: #1d4ed8;
    }

    /* PAGINATION */
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .shop-hero {
            padding: 20px;
        }
        .shop-hero-title {
            font-size: 22px;
        }
        .toolbar-search-form {
            max-width: 100%;
        }
        .toolbar-cat-select {
            width: 100%;
        }
        .toolbar-sort-wrap {
            width: 100%;
            justify-content: space-between;
        }
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
        }
        .product-image-container {
            height: 180px;
        }
        .product-card-body {
            padding: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="shop-container">

    <!-- HERO / BREADCRUMBS -->
    <div class="shop-hero">
        <div class="shop-breadcrumbs">
            <a href="{{ route('home') }}">🏠 {{ __('messages.nav_home') }}</a>
            <span class="sep">/</span>
            <a href="{{ route('shop.products') }}">🛍️ {{ __('messages.nav_products') }}</a>
            @if($activeCategory)
                <span class="sep">/</span>
                <span class="current">{{ $activeCategory->name }}</span>
            @endif
        </div>

        <div class="shop-hero-flex">
            <div>
                <h1 class="shop-hero-title">
                    @if($activeCategory)
                        <span>{{ $activeCategory->icon ?? '📦' }}</span> {{ $activeCategory->name }}
                    @else
                        <span>🛍️</span> {{ __('messages.products_header_title') }}
                    @endif
                </h1>
                <p class="shop-hero-subtitle">
                    @if($activeCategory)
                        {{ app()->getLocale() === 'sw' 
                            ? 'Gundua bidhaa bora na nafuu kutoka kwa wauzaji walioidhinishwa katika kategoria hii.' 
                            : 'Discover verified and affordable products from approved vendors in this category.' }}
                    @else
                        {{ app()->getLocale() === 'sw' 
                            ? 'Nunua bidhaa yoyote kwa usalama ukitumia mfumo wa ulinzi wa malipo (Escrow Protection).' 
                            : 'Shop with full confidence using our 100% Escrow Buyer Protection.' }}
                    @endif
                </p>
            </div>

            <div class="shop-hero-count">
                <span>📦</span> {{ $products->total() }} {{ app()->getLocale() === 'sw' ? 'Bidhaa Zilizopo' : 'Items Found' }}
            </div>
        </div>
    </div>

    <!-- HORIZONTAL SCROLLING CATEGORY CHIPS BAR -->
    <div class="category-scroll-wrapper">
        <div class="category-scroll-track">
            <!-- All Products Chip -->
            <a href="{{ route('shop.products', request()->only(['search', 'sort'])) }}" 
               class="cat-chip {{ !request('category') ? 'active' : '' }}">
                <span>✨</span>
                <span>{{ app()->getLocale() === 'sw' ? 'Zote' : 'All Products' }}</span>
                <span class="chip-count">{{ $categories->sum('products_count') }}</span>
            </a>

            <!-- Each Category Chip -->
            @foreach($categories as $cat)
                @php
                    $isActive = request('category') == $cat->id || request('category') == $cat->slug;
                @endphp
                <a href="{{ route('shop.products', array_merge(request()->only(['search', 'sort']), ['category' => $cat->slug])) }}" 
                   class="cat-chip {{ $isActive ? 'active' : '' }}">
                    <span>{{ $cat->icon ?? '📦' }}</span>
                    <span>{{ $cat->name }}</span>
                    @if($cat->products_count > 0)
                        <span class="chip-count">{{ $cat->products_count }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- INTEGRATED SEARCH & SORT TOOLBAR -->
    <div class="shop-toolbar">
        <form action="{{ route('shop.products') }}" method="GET" class="toolbar-search-form">
            <!-- Category Dropdown Select -->
            <select name="category" class="toolbar-cat-select" onchange="this.form.submit()">
                <option value="">🏷️ {{ app()->getLocale() === 'sw' ? 'Kategoria Zote (' . $categories->count() . ')' : 'All Categories (' . $categories->count() . ')' }}</option>
                @foreach($categories as $cat)
                    @php
                        $isSelected = request('category') == $cat->id || request('category') == $cat->slug;
                    @endphp
                    <option value="{{ $cat->slug }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $cat->icon ?? '📦' }} {{ $cat->name }} ({{ $cat->products_count }})
                    </option>
                @endforeach
            </select>

            <!-- Search Bar -->
            <div class="toolbar-search-input-wrap">
                <input type="text" name="search" class="toolbar-search-input" 
                       placeholder="{{ __('messages.search_placeholder') }}" 
                       value="{{ request('search') }}">
                <button type="submit" class="toolbar-search-btn">
                    <span>🔍</span> {{ __('messages.search_btn') }}
                </button>
            </div>

            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
        </form>

        <!-- Sort Control -->
        <div class="toolbar-sort-wrap">
            <span class="toolbar-sort-label">⇅ {{ app()->getLocale() === 'sw' ? 'Panga Kwa:' : 'Sort By:' }}</span>
            <select class="toolbar-sort-select" onchange="updateSort(this.value)">
                <option value="" {{ !request('sort') ? 'selected' : '' }}>
                    ⭐ {{ app()->getLocale() === 'sw' ? 'Mpya Zaidi' : 'Newest First' }}
                </option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                    💵 {{ app()->getLocale() === 'sw' ? 'Bei: Ndogo kwenda Kubwa' : 'Price: Low to High' }}
                </option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                    💰 {{ app()->getLocale() === 'sw' ? 'Bei: Kubwa kwenda Ndogo' : 'Price: High to Low' }}
                </option>
                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                    🔥 {{ app()->getLocale() === 'sw' ? 'Zinazopendwa Zaidi' : 'Most Popular' }}
                </option>
            </select>
        </div>
    </div>

    <!-- PRODUCTS GRID -->
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                @php
                    $vendorStore = $product->vendor ? ($product->vendor->shop_name ?: $product->vendor->name) : 'TanzaMart Store';
                    $hasStock = ($product->stock > 0);
                @endphp
                <div class="product-card-modern">
                    <!-- Badges -->
                    <div class="product-card-badge">
                        <span>🛡️</span> Escrow
                    </div>

                    <!-- Product Image -->
                    <a href="{{ route('shop.product.details', $product->id) }}" class="product-image-container">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.src='{{ asset('images/logo.jpg') }}'">
                    </a>

                    <!-- Product Details -->
                    <div class="product-card-body">
                        <div>
                            @if($product->category)
                                <a href="{{ route('shop.products', ['category' => $product->category->slug]) }}" class="product-cat-tag">
                                    {{ $product->category->icon ?? '🏷️' }} {{ $product->category->name }}
                                </a>
                            @endif

                            <a href="{{ route('shop.product.details', $product->id) }}" class="product-title-link" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>

                            <div class="product-vendor-row">
                                <span>🏪 {{ $vendorStore }}</span>
                            </div>
                        </div>

                        <!-- Price & Stock -->
                        <div>
                            <div class="product-price-row">
                                <span class="product-price-val">TZS {{ number_format($product->price) }}</span>
                                @if($hasStock)
                                    <span class="product-stock-badge in-stock">✓ {{ __('messages.in_stock') }}</span>
                                @else
                                    <span class="product-stock-badge low-stock">✕ {{ __('messages.out_of_stock') }}</span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="product-card-actions">
                                @if($hasStock)
                                    <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart" title="{{ __('messages.add_to_cart') }}">
                                            <span>🛒</span> {{ __('messages.add_to_cart') }}
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-add-cart" style="background: #94a3b8; cursor: not-allowed;" disabled>
                                        {{ __('messages.out_of_stock') }}
                                    </button>
                                @endif

                                <a href="{{ route('shop.product.details', $product->id) }}" class="btn-view-details" title="{{ __('messages.btn_details') }}">
                                    <span>👁️</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @endif

    @else
        <!-- EMPTY STATE -->
        <div class="empty-state-card">
            <div class="empty-icon">🔍</div>
            <h3 class="empty-title">
                {{ app()->getLocale() === 'sw' ? 'Hakuna Bidhaa Zilizopatikana' : 'No Products Found' }}
            </h3>
            <p class="empty-desc">
                @if(request('search'))
                    {{ app()->getLocale() === 'sw' 
                        ? 'Hatukupata bidhaa inayolingana na neno ulilotafuta: "' . request('search') . '". Tafadhali jaribu neno lingine au angalia kategoria zote.' 
                        : 'No products matched your search term "' . request('search') . '". Please check your spelling or view other categories.' }}
                @else
                    {{ app()->getLocale() === 'sw' 
                        ? 'Kwa sasa hakuna bidhaa zilizowekwa kwenye kategoria hii. Wauzaji wanaendelea kupakia bidhaa mpya hivi karibuni.' 
                        : 'Currently there are no active products in this category. Vendors are adding new items regularly.' }}
                @endif
            </p>
            <a href="{{ route('shop.products') }}" class="btn-reset-filter">
                <span>🛍️</span> {{ app()->getLocale() === 'sw' ? 'Tazama Bidhaa Zote' : 'View All Products' }}
            </a>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function updateSort(sortVal) {
        const url = new URL(window.location.href);
        if (sortVal) {
            url.searchParams.set('sort', sortVal);
        } else {
            url.searchParams.delete('sort');
        }
        window.location.href = url.toString();
    }
</script>
@endpush
@endsection
