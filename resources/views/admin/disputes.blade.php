@extends('layouts.admin')

@section('title', __('messages.sidebar_disputes'))
@section('page_title', __('messages.sidebar_disputes'))

@section('content')
<style>
    .disputes-wrapper {
        max-width: 1280px;
        margin: 25px auto 60px;
        padding: 0 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }
    .page-title h1 {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }
    .page-title p {
        font-size: 14.5px;
        color: #64748b;
        margin: 0;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #00bcd4;
        color: #ffffff !important;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 3px 10px rgba(0, 188, 212, 0.25);
        transition: all 0.2s ease;
    }
    .btn-back:hover {
        background: #0097a7;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 188, 212, 0.35);
    }

    /* KPI Summary Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }
    .kpi-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
    }
    .kpi-card.warning::before { background: #f59e0b; }
    .kpi-card.success::before { background: #10b981; }
    .kpi-card.info::before { background: #00bcd4; }
    
    .kpi-info h3 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .kpi-info .number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .kpi-info span.subtext {
        font-size: 12.5px;
        color: #94a3b8;
        display: block;
        margin-top: 4px;
    }
    .kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .kpi-card.warning .kpi-icon { background: #fef3c7; color: #b45309; }
    .kpi-card.success .kpi-icon { background: #d1fae5; color: #047857; }
    .kpi-card.info .kpi-icon { background: #e0f7fa; color: #00838f; }

    /* Filter & Search Bar */
    .filter-bar {
        background: #ffffff;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: #475569;
        background: #f1f5f9;
        transition: all 0.2s;
    }
    .filter-tab:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .filter-tab.active {
        background: #1e293b;
        color: #ffffff;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 6px 12px;
        width: 320px;
        max-width: 100%;
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13.5px;
        width: 100%;
        color: #1e293b;
    }
    .search-box button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        color: #64748b;
    }

    /* Main Table Container */
    .table-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .table-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-card-header h2 {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .badge-count {
        background: #e2e8f0;
        color: #334155;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .table-responsive {
        overflow-x: auto;
        width: 100%;
    }
    table.disputes-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 1000px;
    }
    table.disputes-table thead tr {
        background: #1e293b;
        color: #ffffff;
    }
    table.disputes-table th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border: none;
    }
    table.disputes-table td {
        padding: 16px 16px;
        font-size: 13.5px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        color: #334155;
    }
    table.disputes-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Column Styles */
    .order-tag {
        font-family: 'Courier New', monospace, sans-serif;
        font-weight: 800;
        color: #0284c7;
        font-size: 14px;
        display: block;
        margin-bottom: 4px;
    }
    .order-amount {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .customer-info, .vendor-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .name-bold {
        font-weight: 700;
        color: #0f172a;
    }
    .meta-sub {
        font-size: 12px;
        color: #64748b;
    }
    .reason-box {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-left: 3px solid #f59e0b;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        color: #92400e;
        max-width: 280px;
        line-height: 1.4;
        word-break: break-word;
    }
    .date-text {
        font-size: 12.5px;
        color: #64748b;
        white-space: nowrap;
    }

    /* Badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .badge-status.open {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .badge-status.resolved {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .badge-status.review {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    /* Action Buttons */
    .actions-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 170px;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        width: 100%;
    }
    .btn-action.release {
        background: #10b981;
        color: #ffffff;
    }
    .btn-action.release:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    .btn-action.refund {
        background: #ef4444;
        color: #ffffff;
    }
    .btn-action.refund:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
    }
    .btn-action.modal-trigger {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    .btn-action.modal-trigger:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .resolution-note {
        font-size: 12px;
        color: #047857;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 6px 10px;
        border-radius: 6px;
        line-height: 1.35;
        max-width: 200px;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #64748b;
    }
    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 12px;
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .empty-state p {
        font-size: 14px;
        color: #94a3b8;
    }

    /* Custom Modal */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-backdrop.show {
        display: flex;
    }
    .modal-dialog {
        background: #ffffff;
        border-radius: 14px;
        width: 92%;
        max-width: 520px;
        box-shadow: 0 20px 35px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: modalSlide 0.25s ease-out;
    }
    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-header {
        padding: 18px 24px;
        background: #1e293b;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-close-modal {
        background: none;
        border: none;
        color: #cbd5e1;
        font-size: 20px;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
    }
    .btn-close-modal:hover { color: #ffffff; }
    .modal-body {
        padding: 22px 24px;
    }
    .modal-detail-row {
        margin-bottom: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
    }
    .modal-detail-row strong {
        display: block;
        font-size: 11.5px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .modal-detail-row span {
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-group textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13.5px;
        outline: none;
        resize: vertical;
        box-sizing: border-box;
        font-family: inherit;
    }
    .form-group textarea:focus {
        border-color: #00bcd4;
        box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
    }
    .modal-footer {
        padding: 16px 24px;
        background: #fafbfc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .disputes-wrapper {
            padding: 0 12px;
            margin-top: 15px;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-box {
            width: 100%;
        }
    }
</style>

<div class="disputes-wrapper">

    <!-- Flash Messages -->
    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:14px 18px; border-radius:8px; margin-bottom:22px; font-weight:bold; border-left:4px solid #28a745; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da; color:#721c24; padding:14px 18px; border-radius:8px; margin-bottom:22px; font-weight:bold; border-left:4px solid #dc3545; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>⚠️ Usimamizi wa Migogoro (Disputes)</h1>
            <p>Kagua malalamiko ya wateja na uamue kama utatoa pesa kwa muuzaji (Release) au utamrudishia mteja (Refund).</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <span>←</span> Rudi Dashibodi
        </a>
    </div>

    <!-- KPI Metric Cards -->
    <div class="kpi-grid">
        <div class="kpi-card warning">
            <div class="kpi-info">
                <h3>Migogoro Inayosubiri</h3>
                <div class="number">{{ $openCount ?? 0 }}</div>
                <span class="subtext">Inahitaji uamuzi wako sasa</span>
            </div>
            <div class="kpi-icon">⏳</div>
        </div>

        <div class="kpi-card success">
            <div class="kpi-info">
                <h3>Iliyotatuliwa (Resolved)</h3>
                <div class="number">{{ $resolvedCount ?? 0 }}</div>
                <span class="subtext">Migogoro iliyokamilika salama</span>
            </div>
            <div class="kpi-icon">✅</div>
        </div>

        <div class="kpi-card info">
            <div class="kpi-info">
                <h3>Jumla ya Malalamiko</h3>
                <div class="number">{{ $totalCount ?? 0 }}</div>
                <span class="subtext">Rekodi zote za migogoro</span>
            </div>
            <div class="kpi-icon">📋</div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="filter-bar">
        <div class="filter-tabs">
            <a href="{{ route('admin.disputes') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
                Zote ({{ $totalCount ?? 0 }})
            </a>
            <a href="{{ route('admin.disputes', ['status' => 'open']) }}" class="filter-tab {{ request('status') === 'open' ? 'active' : '' }}">
                ⚠️ Inasubiri ({{ $openCount ?? 0 }})
            </a>
            <a href="{{ route('admin.disputes', ['status' => 'resolved']) }}" class="filter-tab {{ request('status') === 'resolved' ? 'active' : '' }}">
                ✅ Imetatuliwa ({{ $resolvedCount ?? 0 }})
            </a>
        </div>

        <form action="{{ route('admin.disputes') }}" method="GET" class="search-box">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" placeholder="Tafuta kwa Oda, Mteja au Sababu..." value="{{ request('search') }}">
            <button type="submit" title="Tafuta">🔍</button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span>📜</span> Orodha ya Migogoro na Malalamiko
            </h2>
            <span class="badge-count">{{ $disputes->total() ?? $disputes->count() }} Jumla</span>
        </div>

        @if($disputes->count() > 0)
            <div class="table-responsive">
                <table class="disputes-table">
                    <thead>
                        <tr>
                            <th style="width: 14%;">Oda #</th>
                            <th style="width: 16%;">Mteja</th>
                            <th style="width: 16%;">Muuzaji</th>
                            <th style="width: 22%;">Sababu ya Malalamiko</th>
                            <th style="width: 12%;">Tarehe</th>
                            <th style="width: 10%;">Hali</th>
                            <th style="width: 10%; text-align: center;">Maamuzi (Actions)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disputes as $d)
                            @php
                                $order = $d->order;
                                $user = $d->user;
                                $vendor = $d->vendor;
                                if (!$vendor && $order && $order->items->first()) {
                                    $firstItem = $order->items->first();
                                    $vendor = $firstItem->product ? $firstItem->product->vendor : null;
                                }
                                $orderNum = $order ? ($order->order_number ?? ('#' . $order->id)) : ('#' . $d->order_id);
                                $orderTotal = $order ? $order->total : 0;
                                $isOpen = (strtolower($d->status) === 'open' || strtolower($d->status) === 'pending');
                            @endphp
                            <tr>
                                <!-- Order Info -->
                                <td>
                                    <span class="order-tag">{{ $orderNum }}</span>
                                    <span class="order-amount">TZS {{ number_format($orderTotal) }}</span>
                                </td>

                                <!-- Customer -->
                                <td>
                                    <div class="customer-info">
                                        <span class="name-bold">{{ $user->name ?? ($order->name ?? 'Mteja') }}</span>
                                        <span class="meta-sub">📞 {{ $user->phone ?? ($order->phone ?? 'N/A') }}</span>
                                    </div>
                                </td>

                                <!-- Vendor -->
                                <td>
                                    <div class="vendor-info">
                                        <span class="name-bold">{{ $vendor->shop_name ?? ($vendor->name ?? 'Duka la Muuzaji') }}</span>
                                        <span class="meta-sub">ID: #{{ $vendor->id ?? ($d->vendor_id ?? 'N/A') }}</span>
                                    </div>
                                </td>

                                <!-- Reason -->
                                <td>
                                    <div class="reason-box">
                                        💬 "{{ $d->reason }}"
                                    </div>
                                </td>

                                <!-- Date -->
                                <td>
                                    <span class="date-text">
                                        📅 {{ $d->created_at ? $d->created_at->format('d M, Y') : 'Leo' }}<br>
                                        <small style="color:#94a3b8;">{{ $d->created_at ? $d->created_at->format('H:i') : '' }}</small>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    @if($isOpen)
                                        <span class="badge-status open">⚠️ Inasubiri</span>
                                    @else
                                        <span class="badge-status resolved">✅ Imetatuliwa</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td>
                                    @if($isOpen)
                                        <div class="actions-group">
                                            <!-- Release Funds to Vendor -->
                                            <form action="{{ route('admin.disputes.resolve', $d->id) }}" method="POST" onsubmit="return confirm('Je, una uhakika unataka kuidhinisha na kutoa malipo (Release) kwa muuzaji?');">
                                                @csrf
                                                <input type="hidden" name="action" value="release">
                                                <button type="submit" class="btn-action release" title="Idhinisha Malipo kwa Muuzaji">
                                                    💰 Toa Malipo (Release)
                                                </button>
                                            </form>

                                            <!-- Refund to Customer -->
                                            <form action="{{ route('admin.disputes.resolve', $d->id) }}" method="POST" onsubmit="return confirm('Je, una uhakika unataka kumrudishia mteja fedha zake (Refund)? Hii itarudisha pia bidhaa stoo.');">
                                                @csrf
                                                <input type="hidden" name="action" value="refund">
                                                <button type="submit" class="btn-action refund" title="Rudisha Pesa kwa Mteja">
                                                    ↩️ Rudisha Pesa (Refund)
                                                </button>
                                            </form>

                                            <!-- Open Custom Resolution Modal -->
                                            <button type="button" class="btn-action modal-trigger" onclick="openResolveModal('{{ $d->id }}', '{{ $orderNum }}', '{{ addslashes($user->name ?? 'Mteja') }}', '{{ addslashes($d->reason) }}', '{{ number_format($orderTotal) }}')">
                                                📝 Maelezo / Tatua
                                            </button>
                                        </div>
                                    @else
                                        <div class="resolution-note">
                                            <strong>Utatuzi:</strong><br>
                                            {{ $d->resolution ?: 'Imetatuliwa kikamilifu na Uongozi wa TanzaMart.' }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($disputes->hasPages())
                <div style="padding: 20px; display: flex; justify-content: center; border-top: 1px solid #edf2f7;">
                    {{ $disputes->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="icon">🎉</div>
                <h3>Hakuna migogoro iliyofunguliwa kwa sasa</h3>
                <p>Miamala yote inakwenda salama na hakuna malalamiko yanayosubiri utatuzi.</p>
            </div>
        @endif
    </div>

</div>

<!-- Custom Resolution Modal -->
<div id="resolveModal" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3><span>⚖️</span> Fanya Maamuzi ya Mgogoro</h3>
            <button type="button" class="btn-close-modal" onclick="closeResolveModal()">&times;</button>
        </div>

        <form id="resolveModalForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="modal-detail-row">
                    <strong>Namba ya Oda:</strong>
                    <span id="modalOrderNum">-</span>
                    <div style="margin-top: 4px; font-size: 13px; color: #64748b;">
                        Kiasi: <strong style="color: #0f172a;" id="modalOrderAmount">-</strong>
                    </div>
                </div>

                <div class="modal-detail-row">
                    <strong>Mteja & Sababu:</strong>
                    <span id="modalCustomerName">-</span>
                    <p id="modalDisputeReason" style="margin: 6px 0 0; font-size: 13px; color: #92400e; background: #fffbeb; padding: 6px 10px; border-radius: 6px; border: 1px solid #fef3c7;">-</p>
                </div>

                <div class="form-group">
                    <label for="resolution_note">Maelezo ya Utatuzi (Resolution Note):</label>
                    <textarea name="resolution" id="resolution_note" rows="3" placeholder="Andika maelezo ya uamuzi kwa pande zote mbili..."></textarea>
                </div>

                <input type="hidden" name="action" id="modalActionInput" value="release">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-action modal-trigger" style="width: auto; padding: 8px 16px;" onclick="closeResolveModal()">Ghairi</button>
                <button type="button" class="btn-action refund" style="width: auto; padding: 8px 16px;" onclick="submitDecision('refund')">↩️ Rudisha kwa Mteja</button>
                <button type="button" class="btn-action release" style="width: auto; padding: 8px 16px;" onclick="submitDecision('release')">💰 Idhinisha kwa Muuzaji</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResolveModal(id, orderNum, customer, reason, amount) {
        const form = document.getElementById('resolveModalForm');
        form.action = "{{ url('/admin/disputes') }}/" + id + "/resolve";
        document.getElementById('modalOrderNum').innerText = orderNum;
        document.getElementById('modalCustomerName').innerText = customer;
        document.getElementById('modalDisputeReason').innerText = '"' + reason + '"';
        document.getElementById('modalOrderAmount').innerText = 'TZS ' + amount;
        document.getElementById('resolution_note').value = '';
        document.getElementById('resolveModal').classList.add('show');
    }

    function closeResolveModal() {
        document.getElementById('resolveModal').classList.remove('show');
    }

    function submitDecision(action) {
        const confirmMsg = action === 'release' 
            ? 'Je, una uhakika unataka kuidhinisha malipo haya yaende kwa muuzaji?' 
            : 'Je, una uhakika unataka kurudisha fedha hizi kwa mteja na kurudisha bidhaa stoo?';
        
        if (confirm(confirmMsg)) {
            document.getElementById('modalActionInput').value = action;
            document.getElementById('resolveModalForm').submit();
        }
    }

    // Close modal on click outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('resolveModal');
        if (e.target === modal) {
            closeResolveModal();
        }
    });
</script>
@endsection
