@extends('layouts.admin')

@section('title', __('messages.sidebar_dashboard'))
@section('page_title', __('messages.sidebar_dashboard'))

@section('styles')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 28px 32px;
        color: #ffffff;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .dash-hero h1 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 6px;
    }
    .dash-hero p {
        color: #94a3b8;
        font-size: 14.5px;
        max-width: 600px;
        margin: 0;
    }
    .dash-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 188, 212, 0.15);
        border: 1px solid rgba(0, 188, 212, 0.3);
        color: #00bcd4;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .kpi-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    .kpi-card.cyan::before { background: #00bcd4; }
    .kpi-card.amber::before { background: #f59e0b; }
    .kpi-card.rose::before { background: #f43f5e; }
    .kpi-card.emerald::before { background: #10b981; }
    .kpi-card.indigo::before { background: #6366f1; }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .kpi-header h3 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }
    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .kpi-card.cyan .kpi-icon { background: #e0f7fa; color: #00838f; }
    .kpi-card.amber .kpi-icon { background: #fef3c7; color: #b45309; }
    .kpi-card.rose .kpi-icon { background: #ffe4e6; color: #be123c; }
    .kpi-card.emerald .kpi-icon { background: #d1fae5; color: #047857; }
    .kpi-card.indigo .kpi-icon { background: #e0e7ff; color: #4338ca; }

    .kpi-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.1;
        margin-bottom: 8px;
    }
    .kpi-link {
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #0284c7;
        transition: color 0.15s;
    }
    .kpi-link:hover {
        color: #0369a1;
        text-decoration: underline;
    }

    .quick-actions-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        margin-bottom: 30px;
    }
    .quick-actions-card h2 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 16px;
    }
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
    }
    .action-tile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-dark);
        font-size: 13.5px;
        font-weight: 700;
        transition: all 0.2s;
    }
    .action-tile:hover {
        background: #ffffff;
        border-color: var(--primary);
        color: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }
    .action-tile .tile-icon {
        font-size: 20px;
    }
</style>
@endsection

@section('content')

    <!-- Welcome Hero -->
    <div class="dash-hero">
        <div>
            <h1>⚡ {{ __('messages.sidebar_welcome') }} {{ Auth::user()->name ?? 'Admin' }}</h1>
            <p>{{ __('messages.sidebar_role_admin') }} - TanzaMart E-Commerce Administration & Escrow System</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="button" onclick="openChangePasswordModal()" class="dash-badge" style="background: rgba(0, 188, 212, 0.2); border-color: rgba(0, 188, 212, 0.5); color: #00e5ff; cursor: pointer;" title="Badili Nenosiri la Admin">
                <span>🔑</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}
            </button>
            <a href="{{ route('admin.backup.export') }}" class="dash-badge" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.5); color: #34d399; text-decoration: none;" title="Download full JSON data backup">
                <span>💾</span> Pakua Backup ya Mfumo
            </a>
            <span class="dash-badge">
                <span>🛡️</span> {{ __('messages.sidebar_role_admin') }}
            </span>
        </div>
    </div>

    <!-- KPI Metrics Grid -->
    <div class="kpi-grid">
        <!-- 1. Vendors Pending -->
        <div class="kpi-card amber">
            <div class="kpi-header">
                <h3>{{ __('messages.sidebar_vendors') }} Inayosubiri</h3>
                <div class="kpi-icon">🏪</div>
            </div>
            <div class="kpi-number">{{ $pending_vendors_count ?? 0 }}</div>
            <a href="{{ route('admin.vendors') }}" class="kpi-link">
                {{ __('messages.btn_view_details') }} →
            </a>
        </div>

        <!-- 2. Escrow Orders Pending Release -->
        <div class="kpi-card cyan">
            <div class="kpi-header">
                <h3>{{ __('messages.sidebar_escrow') }}</h3>
                <div class="kpi-icon">🔒</div>
            </div>
            <div class="kpi-number">{{ $pending_escrow_count ?? 0 }}</div>
            <a href="{{ route('admin.escrow_payments') }}" class="kpi-link">
                {{ __('messages.btn_view_details') }} →
            </a>
        </div>

        <!-- 3. Withdrawals Pending -->
        <div class="kpi-card indigo">
            <div class="kpi-header">
                <h3>{{ __('messages.sidebar_withdrawals') }}</h3>
                <div class="kpi-icon">💳</div>
            </div>
            <div class="kpi-number">{{ $pending_withdrawal_count ?? 0 }}</div>
            <a href="{{ route('admin.withdrawals') }}" class="kpi-link">
                {{ __('messages.btn_view_details') }} →
            </a>
        </div>

        <!-- 4. Disputes Pending -->
        <div class="kpi-card rose">
            <div class="kpi-header">
                <h3>{{ __('messages.sidebar_disputes') }}</h3>
                <div class="kpi-icon">⚠️</div>
            </div>
            <div class="kpi-number">{{ $pending_disputes_count ?? 0 }}</div>
            <a href="{{ route('admin.disputes') }}" class="kpi-link">
                {{ __('messages.btn_view_details') }} →
            </a>
        </div>

        <!-- 5. Total Revenue -->
        <div class="kpi-card emerald">
            <div class="kpi-header">
                <h3>{{ __('messages.th_amount') }} (Total)</h3>
                <div class="kpi-icon">💰</div>
            </div>
            <div class="kpi-number" style="font-size: 22px; color: #10b981;">
                TZS {{ number_format($total_revenue ?? 0) }}
            </div>
            <a href="{{ route('admin.reports') }}" class="kpi-link">
                {{ __('messages.sidebar_reports') }} →
            </a>
        </div>
    </div>

    <!-- Quick Actions Navigation Tiles -->
    <div class="quick-actions-card">
        <h2>⚡ Vitendo vya Haraka (Quick Management Shortcuts)</h2>
        <div class="actions-grid">
            <a href="{{ route('admin.products') }}" class="action-tile">
                <span class="tile-icon">📦</span>
                <span>{{ __('messages.sidebar_products') }}</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="action-tile">
                <span class="tile-icon">🛒</span>
                <span>{{ __('messages.sidebar_orders') }}</span>
            </a>
            <a href="{{ route('admin.escrow_payments') }}" class="action-tile">
                <span class="tile-icon">🔒</span>
                <span>{{ __('messages.sidebar_escrow') }}</span>
            </a>
            <a href="{{ route('admin.withdrawals') }}" class="action-tile">
                <span class="tile-icon">💳</span>
                <span>{{ __('messages.sidebar_withdrawals') }}</span>
            </a>
            <a href="{{ route('admin.disputes') }}" class="action-tile">
                <span class="tile-icon">⚠️</span>
                <span>{{ __('messages.sidebar_disputes') }}</span>
            </a>
            <a href="{{ route('admin.vendors') }}" class="action-tile">
                <span class="tile-icon">🏪</span>
                <span>{{ __('messages.sidebar_vendors') }}</span>
            </a>
            <a href="{{ route('admin.users') }}" class="action-tile">
                <span class="tile-icon">👥</span>
                <span>{{ __('messages.sidebar_users') }}</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="action-tile">
                <span class="tile-icon">📈</span>
                <span>{{ __('messages.sidebar_reports') }}</span>
            </a>
            <a href="{{ route('admin.backup.export') }}" class="action-tile" style="border-color: rgba(16, 185, 129, 0.45); background: rgba(16, 185, 129, 0.05);">
                <span class="tile-icon">💾</span>
                <span style="color: #10b981; font-weight: 700;">Export Backup Data</span>
            </a>
            <a href="javascript:void(0)" onclick="openChangePasswordModal()" class="action-tile" style="border-color: rgba(0, 188, 212, 0.45); background: rgba(0, 188, 212, 0.05);">
                <span class="tile-icon">🔑</span>
                <span style="color: #0891b2; font-weight: 700;">{{ app()->getLocale() == 'sw' ? 'Badili Nenosiri' : 'Change Password' }}</span>
            </a>
        </div>
    </div>

@endsection
