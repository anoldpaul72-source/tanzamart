@extends('layouts.customer')

@section('title', __('messages.customer_menu_orders') . ' - TanzaMart')

@section('styles')
<style>
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .order-card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        border-top: 4px solid #2563eb;
    }
    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-number {
        font-weight: 700;
        font-size: 1.125rem;
        color: #2563eb;
    }
    .order-date {
        font-size: 0.8125rem;
        color: #64748b;
    }
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #fef3c7;
        color: #b45309;
        margin-left: 6px;
    }
    .status-badge.completed, .status-badge.received {
        background: #dcfce7;
        color: #15803d;
    }
    .status-badge.refunded {
        background: #fee2e2;
        color: #b91c1c;
    }
    .status-badge.processing {
        background: #e0f2fe;
        color: #0369a1;
    }
    .status-badge.shipped, .status-badge.delivered {
        background: #ede9fe;
        color: #6d28d9;
    }
    .items-gallery {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .item-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        min-width: 200px;
    }
    .item-thumbnail {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        object-fit: cover;
        background: #0f172a;
    }
    .item-info {
        font-size: 0.8125rem;
    }
    .item-info strong {
        display: block;
        color: #0f172a;
        font-weight: 600;
    }
    .item-info span {
        color: #64748b;
        font-size: 0.75rem;
    }
    .order-details {
        font-size: 0.875rem;
        color: #475569;
        margin-bottom: 16px;
        line-height: 1.6;
    }
    .order-details p { margin: 4px 0; }
    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #f1f5f9;
        padding-top: 14px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .total-price {
        font-size: 1.125rem;
        font-weight: 800;
        color: #15803d;
    }
    .footer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-receipt {
        background: #0f172a;
        color: white;
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-receipt:hover { background: #2563eb; }
    .btn-confirm {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-confirm:hover { background: #059669; }
    .btn-dispute {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-dispute:hover { background: #ef4444; color: white; }
    .badge-confirmed {
        color: #15803d;
        font-weight: 600;
        font-size: 0.8125rem;
        background: #dcfce7;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-dispute-pending {
        color: #b45309;
        font-weight: 600;
        font-size: 0.8125rem;
        background: #fef3c7;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-dispute-resolved {
        color: #15803d;
        font-weight: 600;
        font-size: 0.8125rem;
        background: #dcfce7;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid #bbf7d0;
    }
    .empty-orders {
        background: white;
        padding: 48px 24px;
        text-align: center;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }

    /* Dispute Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        padding: 15px;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
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
        background: #991b1b;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { font-size: 1rem; font-weight: 600; margin: 0; }
    .modal-close {
        background: transparent;
        border: none;
        color: #fca5a5;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover { color: #fff; }
    .modal-body { padding: 22px; }
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
            📦 {{ __('messages.customer_menu_orders') }}
        </h1>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            {{ app()->getLocale() === 'sw' 
                ? 'Tazama historia ya manunuzi yako, thibitisha mzigo kupokelewa au kufungua kesi.' 
                : 'View order history, confirm package receipt or open a dispute with admin support.' }}
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <button type="button" onclick="openChangePasswordModal()" style="background: rgba(37, 99, 235, 0.12); color: #1d4ed8; border: 1px solid rgba(37, 99, 235, 0.35); padding: 8px 16px; border-radius: 8px; font-size: 0.8125rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            🔑 {{ app()->getLocale() === 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
        </button>
        <a href="{{ route('shop.products') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.8125rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            🛍️ {{ __('messages.customer_menu_shop') }}
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

<div class="orders-list">
    @forelse($orders as $order)
        @php
            $order_id = $order->id;
            $current_status = strtolower($order->status ?? 'pending');
            $delivery_confirmed = $order->delivery_confirmed ?? 0;
            $payment_status = strtolower($order->payment_status ?? 'held');
            $latestDispute = ($order->disputes && $order->disputes->count() > 0) 
                ? $order->disputes->sortByDesc('id')->first() 
                : null;
            $dispute_status = $latestDispute ? strtolower($latestDispute->status) : 'none';
            $has_dispute = ($latestDispute !== null);
        @endphp

        <div class="order-card">
            <div class="order-head">
                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <span class="order-number">{{ __('messages.order_number') }} #{{ $order_id }}</span>
                    @php
                        $badgeClass = '';
                        if (in_array($current_status, ['completed', 'received'])) {
                            $badgeClass = 'completed';
                        } elseif ($current_status === 'refunded') {
                            $badgeClass = 'refunded';
                        } elseif ($current_status === 'processing') {
                            $badgeClass = 'processing';
                        } elseif (in_array($current_status, ['shipped', 'delivered'])) {
                            $badgeClass = 'shipped';
                        }
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">
                        {{ ucfirst($order->status ?? 'Pending') }}
                    </span>
                </div>
                <span class="order-date">{{ __('messages.date') }}: {{ $order->created_at ? $order->created_at->format('d M, Y') : 'N/A' }}</span>
            </div>

            <div class="items-gallery">
                @forelse($order->items as $item)
                    @php
                        $prod = $item->product;
                        $imgName = $prod ? $prod->image : '';
                        $firstImg = explode(',', $imgName)[0] ?? '';
                        $firstImg = trim($firstImg);

                        if (!empty($firstImg)) {
                            if (filter_var($firstImg, FILTER_VALIDATE_URL)) {
                                $displayImg = $firstImg;
                            } elseif (file_exists(public_path('Images/' . $firstImg))) {
                                $displayImg = asset('Images/' . $firstImg);
                            } elseif (file_exists(public_path('images/' . $firstImg))) {
                                $displayImg = asset('images/' . $firstImg);
                            } elseif (file_exists(public_path('uploads/' . $firstImg))) {
                                $displayImg = asset('uploads/' . $firstImg);
                            } else {
                                $displayImg = asset('Images/' . $firstImg);
                            }
                        } else {
                            $displayImg = asset('Images/logo.jpg');
                        }

                        $pName = $prod ? $prod->name : ($item->name ?? 'Bidhaa #' . $item->product_id);
                    @endphp
                    <div class="item-box">
                        <img src="{{ $displayImg }}" alt="{{ $pName }}" class="item-thumbnail" onerror="this.src='{{ asset('Images/logo.jpg') }}'">
                        <div class="item-info">
                            <strong>{{ $pName }}</strong>
                            <span>{{ app()->getLocale() === 'sw' ? 'Idadi' : 'Qty' }}: {{ $item->quantity }}</span>
                        </div>
                    </div>
                @empty
                    <span style="font-size: 0.8125rem; color: #94a3b8;">{{ app()->getLocale() === 'sw' ? 'Taarifa za bidhaa hazipatikani' : 'Item details not found' }}</span>
                @endforelse
            </div>

            <div class="order-details">
                @if(!empty($order->city) || !empty($order->address))
                    <p>📍 <strong>{{ app()->getLocale() === 'sw' ? 'Mahali:' : 'Delivery Address:' }}</strong> {{ $order->city ? $order->city . ', ' : '' }}{{ $order->address }}</p>
                @endif
                @if(!empty($order->transaction_id))
                    <p>💳 <strong>{{ app()->getLocale() === 'sw' ? 'Kumbukumbu ya Muamala:' : 'Transaction Ref:' }}</strong> <span style="color:#2563eb; font-weight: bold;">{{ $order->transaction_id }}</span></p>
                @endif
                <p>🔒 <strong>{{ app()->getLocale() === 'sw' ? 'Hali ya Malipo ya Escrow:' : 'Escrow Payment Status:' }}</strong> 
                    <span style="color: {{ in_array($payment_status, ['released', 'completed']) ? '#16a34a' : (($payment_status === 'dispute' || $payment_status === 'refunded') ? '#dc2626' : '#d97706') }}; font-weight: bold;">
                        @if(in_array($payment_status, ['released', 'completed']))
                            ✓ {{ app()->getLocale() === 'sw' ? 'Yameachiwa (Released)' : 'Released to Vendor' }}
                        @elseif($payment_status === 'refunded')
                            ✕ {{ app()->getLocale() === 'sw' ? 'Yamerudishwa (Refunded)' : 'Refunded to Customer' }}
                        @elseif($payment_status === 'dispute')
                            ⚠️ {{ app()->getLocale() === 'sw' ? 'Kwenye Mgogoro (Dispute)' : 'Under Dispute Review' }}
                        @else
                            🔒 {{ app()->getLocale() === 'sw' ? 'Imeshikiliwa (Held)' : 'Safely Held in Escrow' }}
                        @endif
                    </span>
                </p>
            </div>

            <div class="order-footer">
                <div>
                    <span style="font-size: 0.75rem; color: #64748b;">{{ app()->getLocale() === 'sw' ? 'Jumla iliyolipwa:' : 'Total Amount Paid:' }}</span>
                    <div class="total-price">TZS {{ number_format($order->total) }}</div>
                </div>

                <div class="footer-actions">
                    <a href="{{ route('order.receipt', $order_id) }}" target="_blank" class="btn-receipt">
                        🖨️ {{ app()->getLocale() === 'sw' ? 'Chapisha Risiti' : 'Receipt' }}
                    </a>

                    <!-- Dispute button / Dispute Status -->
                    @if(!$has_dispute)
                        @if($payment_status !== 'released' && $payment_status !== 'refunded')
                            <button type="button" class="btn-dispute" onclick="openDisputeModal({{ $order_id }}, '{{ $order->order_number ?? ('#' . $order_id) }}')">
                                ⚠️ {{ app()->getLocale() === 'sw' ? 'Fungua Malalamiko' : 'Open Dispute' }}
                            </button>
                        @endif
                    @elseif(in_array($dispute_status, ['open', 'pending', 'under review']))
                        <span class="badge-dispute-pending">
                            ⏳ {{ app()->getLocale() === 'sw' ? 'Malalamiko Yanachunguzwa na Admin' : 'Dispute Under Admin Review' }}
                        </span>
                    @elseif($dispute_status === 'resolved')
                        <div style="display:inline-flex; flex-direction:column; gap:4px; align-items:flex-start;">
                            <span class="badge-dispute-resolved">
                                ✓ {{ app()->getLocale() === 'sw' ? 'Malalamiko Yametatuliwa' : 'Dispute Resolved by Admin' }}
                            </span>
                            @if(!empty($latestDispute->resolution))
                                <span style="color:#15803d; font-size:0.75rem; background:#dcfce7; padding:3px 8px; border-radius:6px; border:1px solid #bbf7d0;">
                                    <strong>{{ app()->getLocale() === 'sw' ? 'Utatuzi:' : 'Resolution:' }}</strong> {{ $latestDispute->resolution }}
                                </span>
                            @endif
                        </div>
                    @elseif($dispute_status === 'rejected')
                        <div style="display:inline-flex; flex-direction:column; gap:4px; align-items:flex-start;">
                            <span style="color:#b91c1c; font-weight:600; font-size:0.8125rem; background:#fee2e2; padding:6px 12px; border-radius:6px; display:inline-flex; align-items:center; gap:5px; border:1px solid #fecaca;">
                                ✕ {{ app()->getLocale() === 'sw' ? 'Malalamiko Yamekataliwa' : 'Dispute Rejected' }}
                            </span>
                            @if(!empty($latestDispute->resolution))
                                <span style="color:#991b1b; font-size:0.75rem; background:#fef2f2; padding:3px 8px; border-radius:6px; border:1px solid #fee2e2;">
                                    <strong>{{ app()->getLocale() === 'sw' ? 'Sababu:' : 'Reason:' }}</strong> {{ $latestDispute->resolution }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Delivery confirmation -->
                    @if($delivery_confirmed == 0 && !in_array($current_status, ['received', 'completed', 'refunded']) && $payment_status !== 'dispute')
                        <form method="POST" action="{{ route('order.confirm_delivery', $order_id) }}" style="display:inline; margin:0;" onsubmit="return confirm('{{ app()->getLocale() === 'sw' ? 'Je, una hakika umepokea mzigo huu? Hii itamruhusu Admin kuachia malipo kwa muuzaji.' : 'Are you sure you received this delivery? This will allow Admin to release escrow funds to the vendor.' }}');">
                            @csrf
                            <button type="submit" class="btn-confirm">
                                ✓ {{ app()->getLocale() === 'sw' ? 'Nimepokea Mzigo' : 'Confirm Received' }}
                            </button>
                        </form>
                    @elseif($delivery_confirmed == 1 || in_array($current_status, ['received', 'completed']))
                        <span class="badge-confirmed">
                            ✓ {{ app()->getLocale() === 'sw' ? 'Mzigo Umethibitishwa' : 'Delivery Confirmed' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-orders">
            <h3 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.125rem;">
                {{ app()->getLocale() === 'sw' ? 'Hujafanya oda yoyote bado! 🛒' : 'You have not placed any orders yet! 🛒' }}
            </h3>
            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 0.875rem;">
                {{ app()->getLocale() === 'sw' ? 'Ukishafanya oda, historia yako ya manunuzi itaonekana hapa.' : 'Once you make a purchase, your orders and escrow tracking will appear here.' }}
            </p>
            <a href="{{ route('shop.products') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.875rem; display: inline-block;">
                {{ app()->getLocale() === 'sw' ? 'Anza Manunuzi Sasa' : 'Start Shopping' }}
            </a>
        </div>
    @endforelse

    @if($orders->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<!-- Modal ya Fungua Kesi / Malalamiko -->
<div id="disputeModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>⚠️ {{ app()->getLocale() === 'sw' ? 'Fungua Kesi / Malalamiko ya Oda' : 'Open Order Dispute' }}</h3>
            <button type="button" class="modal-close" onclick="closeDisputeModal()">&times;</button>
        </div>
        <form id="disputeForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <p id="modalOrderInfo" style="font-size: 0.875rem; color: #475569; margin: 0 0 14px 0; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0;"></p>
                
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 6px; color: #334155;">
                        {{ app()->getLocale() === 'sw' ? 'Eleza Tatizo / Sababu ya Kesi:' : 'Explain Problem / Dispute Reason:' }}
                    </label>
                    <textarea name="reason" rows="4" required placeholder="{{ app()->getLocale() === 'sw' ? 'Mfano: Mzigo haujafika kwa wakati uliokubaliwa, au bidhaa ina kasoro...' : 'e.g. Package was not delivered on time, item has defects...' }}" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeDisputeModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; cursor: pointer;">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit" style="background: #dc2626; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-size: 0.8125rem; font-weight: 700; cursor: pointer;">
                    {{ app()->getLocale() === 'sw' ? 'Wasilisha Malalamiko' : 'Submit Dispute' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openDisputeModal(orderId, orderNum) {
        const swMsg = 'Oda: <strong>' + orderNum + '</strong>. Malipo yatasimamishwa kwenye Escrow wakati uongozi unachunguza suala hili.';
        const enMsg = 'Order: <strong>' + orderNum + '</strong>. Escrow payment will be held while administration reviews this case.';
        document.getElementById('modalOrderInfo').innerHTML = ('{{ app()->getLocale() }}' === 'sw') ? swMsg : enMsg;
        document.getElementById('disputeForm').action = '/orders/' + orderId + '/dispute';
        document.getElementById('disputeModal').classList.add('active');
    }

    function closeDisputeModal() {
        document.getElementById('disputeModal').classList.remove('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDisputeModal();
        }
    });
</script>
@endsection
