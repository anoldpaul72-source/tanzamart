@extends('layouts.customer')

@section('title', __('messages.track_page_title') . ' - TanzaMart')

@section('styles')
<style>
    .track-wrapper {
        max-width: 620px;
        margin: 0 auto;
    }
    .track-card {
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }
    .track-header {
        text-align: center;
        margin-bottom: 26px;
    }
    .track-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px 0;
    }
    .track-header p {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
    }
    .input-group {
        margin-bottom: 20px;
    }
    .input-group label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .track-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9375rem;
        outline: none;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: 0.2s;
        box-sizing: border-box;
    }
    .track-input:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
    }
    .btn-track {
        width: 100%;
        padding: 13px;
        background: #06b6d4;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.9375rem;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-track:hover {
        background: #0891b2;
    }
    .result-box {
        margin-top: 28px;
        padding: 22px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .result-box h4 {
        margin: 0 0 10px 0;
        color: #0f172a;
        font-size: 0.9375rem;
        font-weight: 700;
    }
    .status-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.8125rem;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .status-pending { background: #fef3c7; color: #b45309; }
    .status-shipping { background: #e0f2fe; color: #0369a1; }
    .status-completed { background: #dcfce7; color: #15803d; }

    .order-details-list {
        font-size: 0.875rem;
        line-height: 1.8;
        color: #475569;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
    }
    .order-details-list p { margin: 4px 0; }
    .order-details-list strong { color: #0f172a; }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 14px 18px;
        border-radius: 10px;
        text-align: center;
        margin-top: 24px;
        font-size: 0.875rem;
        border: 1px solid #fecaca;
    }
</style>
@endsection

@section('content')
<div class="track-wrapper">
    <div class="track-card">
        <div class="track-header">
            <h2>🔍 {{ __('messages.track_title') }}</h2>
            <p>{{ app()->getLocale() === 'sw' ? 'Weka namba ya muamala au namba ya oda ili kufuatilia hatua ya usafirishaji.' : 'Enter your transaction ref or order ID to track shipment progress.' }}</p>
        </div>
        
        <form method="GET" action="{{ route('order.track') }}">
            <div class="input-group">
                <label>{{ __('messages.label_transaction_id') }}</label>
                <input type="text" name="track_id" class="track-input" placeholder="{{ __('messages.placeholder_transaction_id') }}" value="{{ $trackId ?? '' }}" required autofocus>
            </div>
            <button type="submit" name="track" class="btn-track">🚀 {{ __('messages.btn_track') }}</button>
        </form>

        @if(!empty($searched))
            @if($order)
                @php
                    $rawStatus = strtolower(trim($order->status ?? ''));
                    if (in_array($rawStatus, ['in transit', 'ipo njiani', 'shipping', 'dispatched'])) {
                        $statusDisplay = __('messages.status_shipping');
                        $badgeClass = 'status-shipping';
                    } elseif (in_array($rawStatus, ['delivered', 'completed', 'imekamilika', 'received', 'imepokelewa'])) {
                        $statusDisplay = __('messages.status_completed');
                        $badgeClass = 'status-completed';
                    } else {
                        $statusDisplay = __('messages.status_pending');
                        $badgeClass = 'status-pending';
                    }
                @endphp

                <div class="result-box">
                    <h4>{{ __('messages.order_status_title') }}</h4>
                    <span class="status-badge {{ $badgeClass }}">
                        ● {{ $statusDisplay }}
                    </span>

                    <div class="order-details-list">
                        <p><strong>{{ __('messages.customer_name') }}:</strong> {{ $order->name }}</p>
                        <p><strong>{{ __('messages.destination_city') }}:</strong> {{ $order->city ?? $order->address }}</p>
                        <p><strong>{{ __('messages.total_paid') }}:</strong> TZS {{ number_format($order->total) }}</p>
                        @if($order->order_number)
                            <p><strong>{{ __('messages.order_number') }}:</strong> {{ $order->order_number }}</p>
                        @endif
                        @if(!empty($order->transaction_id))
                            <p><strong>{{ app()->getLocale() === 'sw' ? 'Muamala:' : 'Transaction:' }}</strong> {{ $order->transaction_id }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert-error">
                    ⚠️ {{ __('messages.error_not_found') }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
