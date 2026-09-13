@extends('layouts.admin')

@section('title', __('messages.sidebar_escrow'))
@section('page_title', __('messages.sidebar_escrow'))

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 4px;
    }
    .page-title p {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0;
    }

    .card-table {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .card-table-header {
        padding: 18px 22px;
        background: #fafbfc;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-table-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    table.data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 900px;
    }
    table.data-table thead tr {
        background: #1e293b;
        color: #ffffff;
    }
    table.data-table th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }
    table.data-table td {
        padding: 16px 16px;
        font-size: 13.5px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: #334155;
    }
    table.data-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-release {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-release:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    .badge-confirmed { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-released { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

    .empty-row {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
        font-size: 14px;
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <div class="page-title">
            <h1>🔒 {{ __('messages.sidebar_escrow') }}</h1>
            <p>{{ app()->getLocale() === 'sw' ? 'Simamia na utoe fedha zilizoshikiliwa kwenye escrow kwenda kwa wauzaji pindi mteja akipokea mzigo.' : 'Manage and release funds held in escrow to vendors once delivery is confirmed.' }}</p>
        </div>
    </div>

    <div class="card-table">
        <div class="card-table-header">
            <h2>📜 {{ __('messages.sidebar_escrow') }} ({{ count($orders) }})</h2>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">{{ __('messages.th_order_no') }}</th>
                        <th style="width: 24%;">{{ __('messages.th_vendor') }}</th>
                        <th style="width: 18%;">{{ __('messages.th_amount') }}</th>
                        <th style="width: 22%;">{{ __('messages.th_escrow_status') }}</th>
                        <th style="width: 24%; text-align: center;">{{ __('messages.th_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $row)
                        @php
                            $vendorName = 'PIKIPIKI USED STORE';
                            if ($row->vendor && $row->vendor->shop_name) {
                                $vendorName = $row->vendor->shop_name;
                            } elseif ($row->items && $row->items->first() && $row->items->first()->vendor) {
                                $vendorName = $row->items->first()->vendor->shop_name ?: $row->items->first()->vendor->name;
                            }
                            $isConfirmed = $row->delivery_confirmed || in_array(strtolower($row->status), ['completed', 'delivered', 'received']);
                        @endphp
                        <tr>
                            <td>
                                <strong>#{{ $row->id }}</strong><br>
                                <small style="color: #0284c7; font-weight: bold;">{{ $row->order_number ?? '' }}</small>
                            </td>
                            <td>
                                <strong>{{ $vendorName }}</strong>
                            </td>
                            <td>
                                <strong style="color: #10b981; font-size: 14.5px;">TZS {{ number_format($row->total, 2) }}</strong>
                            </td>
                            <td>
                                @if($isConfirmed)
                                    <span class="badge-status badge-confirmed">
                                        ✔️ {{ app()->getLocale() === 'sw' ? 'Mteja Amethibitisha' : 'Confirmed by Customer' }}
                                    </span>
                                @else
                                    <span class="badge-status badge-pending">
                                        ⏳ {{ app()->getLocale() === 'sw' ? 'Inasubiri Mteja' : 'Pending Customer Confirmation' }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($row->payment_status === 'released')
                                    <span class="badge-status badge-released">
                                        ✔️ {{ __('messages.status_released') }}
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.escrow_payments') }}" onsubmit="return confirm('{{ app()->getLocale() === 'sw' ? 'Je, una uhakika unataka kutoa malipo haya kwa muuzaji?' : 'Are you sure you want to release this payment to the vendor?' }}');" style="display:inline-block; margin:0;">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $row->id }}">
                                        <button type="submit" class="btn-release">
                                            💰 {{ __('messages.btn_release') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">
                                🎉 {{ app()->getLocale() === 'sw' ? 'Hakuna oda zilizosubiri malipo ya escrow kwa sasa.' : 'No escrow payments pending at the moment.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
