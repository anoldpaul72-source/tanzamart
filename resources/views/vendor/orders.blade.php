@extends('layouts.vendor')

@section('title', __('messages.vendor_orders_title') . ' - ' . __('messages.vendor_brand'))

@section('styles')
<style>
    .order-table-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .order-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 950px;
    }
    .order-table th {
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
    .order-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .order-table tr:last-child td { border-bottom: none; }
    .order-table tr:hover { background-color: #f8fafc; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-completed { background: #dcfce7; color: #15803d; }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-refunded { background: #fee2e2; color: #b91c1c; }
    .badge-held { background: #ffedd5; color: #c2410c; }
    .badge-processing { background: #e0f2fe; color: #0369a1; }

    .status-select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 0.8125rem;
        outline: none;
        background: white;
    }
    .status-select:focus { border-color: #6366f1; }
    .btn-save-status {
        background: #06b6d4;
        color: white;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        transition: 0.2s;
    }
    .btn-save-status:hover { background: #0891b2; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
        📑 {{ __('messages.vendor_orders_title') }}
    </h1>
    <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
        {{ __('messages.vendor_orders_desc') }}
    </p>
</div>

@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ⚠️ {{ session('error') }}
    </div>
@endif

<div class="order-table-card">
    <div style="overflow-x: auto;">
        <table class="order-table">
            <thead>
                <tr>
                    <th style="width: 8%;">{{ __('messages.vendor_orders_th_image') }}</th>
                    <th style="width: 10%;">{{ __('messages.vendor_orders_th_order_no') }}</th>
                    <th style="width: 22%;">{{ __('messages.vendor_orders_th_product') }}</th>
                    <th style="width: 15%;">{{ __('messages.vendor_orders_th_customer') }}</th>
                    <th style="width: 14%;">{{ __('messages.vendor_orders_th_escrow_status') }}</th>
                    <th style="width: 14%;">{{ __('messages.vendor_orders_th_payment_status') }}</th>
                    <th style="width: 17%;">{{ __('messages.vendor_orders_th_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orderItems as $item)
                    @php
                        $order = $item->order;
                        $product = $item->product;
                        $paymentStatus = strtolower($order->payment_status ?? 'pending');
                        $orderStatus = strtolower($order->status ?? 'pending');
                        $isDeliveryConfirmed = $order ? ($order->delivery_confirmed || in_array($orderStatus, ['delivered', 'received', 'completed']) || in_array($paymentStatus, ['released', 'completed'])) : false;
                        $itemStatus = $item->status ?? 'Pending';
                        if (in_array($paymentStatus, ['released', 'completed']) || in_array($orderStatus, ['completed'])) {
                            $itemStatus = 'Completed';
                        } elseif ($paymentStatus === 'refunded' || $orderStatus === 'refunded') {
                            $itemStatus = 'Refunded';
                        }
                    @endphp
                    <tr>
                        <td>
                            @if($product && $product->image_url)
                                <img src="{{ $product->image_url }}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                            @else
                                <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">📦</div>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: #0f172a;">#{{ $item->order_id }}</td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">{{ $item->name ?? ($product->name ?? 'Product') }}</div>
                            <div style="color: #64748b; font-size: 0.75rem;">
                                {{ $order && $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #334155;">{{ $order->name ?? (app()->getLocale() === 'sw' ? 'Mteja' : 'Customer') }}</div>
                        </td>
                        
                        <!-- Customer Status (Escrow) -->
                        <td>
                            @if($isDeliveryConfirmed)
                                <span class="status-badge badge-completed">
                                    ✓ {{ __('messages.vendor_orders_escrow_received') }}
                                </span>
                            @else
                                <span class="status-badge badge-pending">
                                    ⏳ {{ __('messages.vendor_orders_escrow_waiting') }}
                                </span>
                            @endif
                        </td>

                        <!-- Payment Status -->
                        <td>
                            @if($paymentStatus === 'released' || $paymentStatus === 'completed')
                                <span class="status-badge badge-completed">
                                    ✓ {{ __('messages.vendor_orders_pay_released') }}
                                </span>
                            @elseif($paymentStatus === 'refunded')
                                <span class="status-badge badge-refunded">
                                    ✕ {{ __('messages.vendor_orders_pay_refunded') }}
                                </span>
                            @else
                                <span class="status-badge badge-held">
                                    🔒 {{ __('messages.vendor_orders_pay_held') }}
                                </span>
                            @endif
                        </td>

                        <!-- Action / Item Status Update -->
                        <td>
                            @if(in_array($paymentStatus, ['released', 'completed']) || in_array(strtolower($itemStatus), ['completed']) || ($order && in_array(strtolower($order->status ?? ''), ['completed', 'received'])))
                                <span style="color: #15803d; font-weight: 700; font-size: 0.8125rem; display: inline-flex; align-items: center; gap: 4px;">
                                    ✓ {{ app()->getLocale() === 'sw' ? 'Imekamilika' : 'Completed' }}
                                </span>
                            @elseif(in_array($paymentStatus, ['refunded']) || in_array(strtolower($itemStatus), ['refunded']) || ($order && strtolower($order->status ?? '') === 'refunded'))
                                <span style="color: #b91c1c; font-weight: 700; font-size: 0.8125rem; display: inline-flex; align-items: center; gap: 4px;">
                                    ✕ {{ app()->getLocale() === 'sw' ? 'Imerejeshwa' : 'Refunded' }}
                                </span>
                            @else
                                <form action="{{ route('vendor.order.status', $item->id) }}" method="POST" style="display: flex; gap: 6px; align-items: center; margin: 0;">
                                    @csrf
                                    <select name="status" class="status-select">
                                        <option value="Pending" {{ $itemStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processing" {{ $itemStatus === 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Shipped" {{ $itemStatus === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $itemStatus === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Completed" {{ $itemStatus === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Refunded" {{ $itemStatus === 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    <button type="submit" class="btn-save-status">{{ __('messages.vendor_orders_btn_save') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 36px; color: #64748b;">
                            {{ __('messages.vendor_orders_no_orders') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($orderItems->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $orderItems->links() }}
    </div>
@endif
@endsection
