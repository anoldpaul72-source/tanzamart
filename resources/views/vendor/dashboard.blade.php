@extends('layouts.vendor')

@section('title', __('messages.vendor_brand') . ' - ' . __('messages.vendor_menu_dash'))

@section('styles')
<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .kpi-card {
        background: white;
        padding: 22px 20px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.06);
    }
    .kpi-title {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
    }
    .kpi-value {
        font-size: 1.625rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .kpi-card-wallet {
        border-left: 4px solid #10b981;
    }
    .kpi-card-wallet .kpi-value {
        color: #10b981;
        font-size: 1.35rem;
    }
    .recent-table-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .recent-table {
        width: 100%;
        border-collapse: collapse;
    }
    .recent-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .recent-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .recent-table tr:last-child td { border-bottom: none; }
    .recent-table tr:hover { background-color: #f8fafc; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 95%;
        max-width: 440px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalIn 0.2s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.96) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
        padding: 18px 22px;
        background: #1e1b4b;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { font-size: 1rem; font-weight: 600; margin: 0; }
    .modal-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover { color: #fff; }
    .modal-body { padding: 22px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        transition: 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
    .modal-footer {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
            {{ __('messages.vendor_page_heading') }}
        </h1>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            {{ app()->getLocale() === 'sw' 
                ? 'Tazama muhtasari wa duka lako, bidhaa, oda na salio la pochi yako.' 
                : 'Monitor your store performance, product catalog, customer orders and wallet balance.' }}
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('home') }}" class="btn-vendor-sm" style="background: #0f172a; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.8125rem; font-weight: 600;">
            ← {{ __('messages.vendor_back_home') }}
        </a>
    </div>
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

<div class="kpi-grid">
    <!-- Products -->
    <div class="kpi-card">
        <div>
            <div class="kpi-title">{{ __('messages.vendor_stat_products') }}</div>
            <div class="kpi-value">{{ $totalProducts ?? 0 }}</div>
        </div>
        <div style="margin-top: 14px;">
            <a href="{{ route('vendor.products') }}" style="color: #6366f1; font-size: 0.8125rem; font-weight: 600; text-decoration: none;">
                {{ app()->getLocale() === 'sw' ? 'Simamia Bidhaa →' : 'Manage Products →' }}
            </a>
        </div>
    </div>

    <!-- Orders -->
    <div class="kpi-card">
        <div>
            <div class="kpi-title">{{ __('messages.vendor_stat_orders') }}</div>
            <div class="kpi-value">{{ $totalOrders ?? 0 }}</div>
        </div>
        <div style="margin-top: 14px;">
            <a href="{{ route('vendor.orders') }}" style="color: #6366f1; font-size: 0.8125rem; font-weight: 600; text-decoration: none;">
                {{ app()->getLocale() === 'sw' ? 'Tazama Oda →' : 'View Orders →' }}
            </a>
        </div>
    </div>

    <!-- Wallet Balance -->
    <div class="kpi-card kpi-card-wallet">
        <div>
            <div class="kpi-title">{{ __('messages.vendor_stat_wallet') }}</div>
            <div class="kpi-value">TZS {{ number_format($walletBalance ?? 0, 2) }}</div>
        </div>
        <div style="margin-top: 14px;">
            <button type="button" onclick="openWithdrawModal()" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: 0.2s;">
                💸 {{ __('messages.vendor_withdraw_link') }}
            </button>
        </div>
    </div>

    <!-- Sales Reports -->
    <div class="kpi-card" style="border-left: 4px solid #f59e0b;">
        <div>
            <div class="kpi-title">{{ __('messages.vendor_reports_title') }}</div>
            <p style="margin: 6px 0 0 0; font-size: 0.75rem; color: #64748b;">
                {{ __('messages.vendor_reports_desc') }}
            </p>
        </div>
        <div style="margin-top: 14px;">
            <a href="{{ route('vendor.orders') }}" style="color: #d97706; font-size: 0.8125rem; font-weight: 600; text-decoration: none;">
                {{ __('messages.vendor_reports_link') }} →
            </a>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div style="margin-bottom: 14px;">
    <h2 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">
        {{ __('messages.vendor_recent_orders') }}
    </h2>
</div>

<div class="recent-table-card">
    <div style="overflow-x: auto;">
        <table class="recent-table">
            <thead>
                <tr>
                    <th>{{ __('messages.vendor_th_order_id') }}</th>
                    <th>{{ __('messages.vendor_th_date') }}</th>
                    <th>{{ __('messages.vendor_th_item_status') }}</th>
                    <th>{{ __('messages.vendor_th_escrow_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    @php
                        $item = $order->items->first();
                        $itemStatus = $item->status ?? $order->status ?? 'Processing';
                        $isRefunded = strtolower($itemStatus) === 'refunded';
                    @endphp
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">#{{ $order->id }}</td>
                        <td style="color: #64748b; font-size: 0.8125rem;">
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            @if($isRefunded)
                                <span style="background: #fee2e2; color: #b91c1c; padding: 3px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ● {{ $itemStatus }}
                                </span>
                            @else
                                <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    ● {{ $itemStatus }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($order->status === 'completed' || $order->delivery_confirmed)
                                <span style="color: #16a34a; font-weight: 700; font-size: 0.8125rem;">
                                    ✓ {{ __('messages.vendor_status_received') }}
                                </span>
                            @else
                                <span style="color: #d97706; font-weight: 600; font-size: 0.8125rem;">
                                    ⏳ {{ __('messages.vendor_status_pending') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #64748b; padding: 36px;">
                            {{ __('messages.vendor_no_orders') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Withdraw Modal -->
<div id="withdrawModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>💸 {{ __('messages.vendor_withdraw_link') }}</h3>
            <button type="button" class="modal-close" onclick="closeWithdrawModal()">&times;</button>
        </div>
        <form action="{{ route('vendor.withdraw') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8125rem; color: #065f46;">
                    {{ app()->getLocale() === 'sw' ? 'Salio lako linaloweza kutolewa:' : 'Available balance:' }}
                    <strong>TZS {{ number_format($walletBalance ?? 0, 2) }}</strong>
                </div>

                <div class="form-group">
                    <label for="withdrawAmount">{{ app()->getLocale() === 'sw' ? 'Kiasi cha Kutoa (TZS):' : 'Amount to Withdraw (TZS):' }}</label>
                    <input type="number" id="withdrawAmount" name="amount" min="10000" max="{{ $walletBalance ?? 0 }}" placeholder="e.g. 50000" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="withdrawPhone">{{ app()->getLocale() === 'sw' ? 'Namba ya Simu (M-Pesa / Tigo Pesa / Airtel):' : 'Phone Number (Mobile Money):' }}</label>
                    <input type="text" id="withdrawPhone" name="phone" value="{{ $vendor->phone ?? '' }}" placeholder="07XXXXXXXX" required class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-vendor-sm" style="background: #e2e8f0; color: #475569; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer;" onclick="closeWithdrawModal()">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit" class="btn-vendor-sm" style="background: #10b981; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 700; cursor: pointer;">
                    {{ app()->getLocale() === 'sw' ? 'Wasilisha Ombi' : 'Submit Request' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openWithdrawModal() {
        document.getElementById('withdrawModal').classList.add('active');
    }
    function closeWithdrawModal() {
        document.getElementById('withdrawModal').classList.remove('active');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeWithdrawModal();
        }
    });
</script>
@endsection
