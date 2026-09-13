@extends('layouts.admin')

@section('title', __('messages.all_products') . ' - TanzaMart Admin')

@section('styles')
<style>
    .product-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }
    .product-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .product-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .product-table tr:last-child td { border-bottom: none; }
    .product-table tr:hover { background-color: #f8fafc; }
    .btn-delete-prod {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: 0.2s;
    }
    .btn-delete-prod:hover {
        background: #ef4444;
        color: white;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
            📦 {{ __('messages.all_products') }}
        </h1>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            {{ app()->getLocale() === 'sw' 
                ? 'Tazama na dhibiti bidhaa zote zilizochapishwa na wauzaji kwenye soko la TanzaMart.' 
                : 'Monitor and moderate all vendor products listed on the TanzaMart marketplace.' }}
        </p>
    </div>
</div>

@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

<div class="product-table-card">
    <div style="overflow-x: auto;">
        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 8%;">{{ app()->getLocale() === 'sw' ? 'Picha' : 'Image' }}</th>
                    <th style="width: 32%;">{{ __('messages.product_name') }}</th>
                    <th style="width: 25%;">{{ __('messages.vendor') }}</th>
                    <th style="width: 15%;">{{ __('messages.price') }}</th>
                    <th style="width: 10%;">{{ __('messages.stock') }}</th>
                    <th style="width: 10%;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $row)
                    <tr>
                        <td>
                            <img src="{{ $row->image_url ?? asset('images/logo.jpg') }}" alt="Product" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;" onerror="this.src='{{ asset('images/logo.jpg') }}'">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">{{ $row->name }}</div>
                            <div style="color: #64748b; font-size: 0.75rem; margin-top: 2px;">
                                ID: #{{ $row->id }} {{ !empty($row->category) ? '• ' . $row->category : '' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #334155;">{{ $row->vendor->shop_name ?? ($row->vendor->name ?? 'N/A') }}</div>
                            <div style="color: #64748b; font-size: 0.75rem;">{{ $row->vendor->email ?? '' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #0f172a;">TZS {{ number_format($row->price, 2) }}</span>
                        </td>
                        <td>
                            @if($row->stock > 5)
                                <span style="font-weight: 600; color: #16a34a; background: #dcfce7; padding: 3px 8px; border-radius: 9999px; font-size: 0.75rem;">
                                    {{ $row->stock }}
                                </span>
                            @elseif($row->stock > 0)
                                <span style="font-weight: 600; color: #b45309; background: #fef3c7; padding: 3px 8px; border-radius: 9999px; font-size: 0.75rem;">
                                    {{ $row->stock }} (Low)
                                </span>
                            @else
                                <span style="font-weight: 600; color: #b91c1c; background: #fee2e2; padding: 3px 8px; border-radius: 9999px; font-size: 0.75rem;">
                                    0 (Out)
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products', ['delete_id' => $row->id]) }}" class="btn-delete-prod" onclick="return confirm('{{ app()->getLocale() === 'sw' ? 'Je, una uhakika unataka kufuta bidhaa hii kwenye mfumo?' : 'Are you sure you want to delete this product from the marketplace?' }}')">
                                🗑️ {{ __('messages.delete') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 36px;">
                            {{ app()->getLocale() === 'sw' ? 'Hakuna bidhaa yoyote iliyopatikana sokoni.' : 'No products found on the marketplace.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
