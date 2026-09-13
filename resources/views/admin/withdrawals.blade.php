@extends('layouts.admin')

@section('title', __('messages.sidebar_withdrawals'))
@section('page_title', __('messages.sidebar_withdrawals'))

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
        min-width: 850px;
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

    .btn-approve {
        background: #10b981;
        color: white;
        border: none;
        padding: 7px 14px;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    .btn-reject {
        background: #ef4444;
        color: white;
        border: none;
        padding: 7px 14px;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-reject:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    .action-form {
        display: flex;
        gap: 8px;
        align-items: center;
    }
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
            <h1>💳 {{ __('messages.sidebar_withdrawals') }}</h1>
            <p>{{ app()->getLocale() === 'sw' ? 'Kagua na uidhinishe maombi ya kutoa fedha kutoka kwenye pochi za wauzaji.' : 'Review and process withdrawal requests from vendor wallets.' }}</p>
        </div>
    </div>

    <div class="card-table">
        <div class="card-table-header">
            <h2>📜 {{ __('messages.sidebar_withdrawals') }} ({{ count($requests) }})</h2>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">{{ __('messages.th_id') }}</th>
                        <th style="width: 25%;">{{ __('messages.th_store') }}</th>
                        <th style="width: 22%;">{{ __('messages.th_phone') }}</th>
                        <th style="width: 15%;">{{ __('messages.th_amount') }}</th>
                        <th style="width: 15%;">{{ __('messages.th_date') }}</th>
                        <th style="width: 15%; text-align: center;">{{ __('messages.th_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $row)
                        <tr>
                            <td><strong>#{{ $row->id }}</strong></td>
                            <td>
                                <strong>{{ $row->vendor->shop_name ?? ($row->vendor->name ?? 'N/A') }}</strong>
                            </td>
                            <td>
                                <strong style="color: #0284c7; font-size: 14px;">{{ $row->phone ?: ($row->vendor->phone ?: '-') }}</strong>
                                @if(!empty($row->vendor->phone) && $row->vendor->phone !== $row->phone)
                                    <br><small style="color: #64748b;">Simu ya Akaunti: {{ $row->vendor->phone }}</small>
                                @endif
                            </td>
                            <td><strong style="color: #10b981; font-size: 14.5px;">TZS {{ number_format($row->amount, 2) }}</strong></td>
                            <td>{{ $row->created_at ? $row->created_at->format('d M, Y') : '-' }}</td>
                            <td style="text-align: center;">
                                <form method="POST" action="{{ route('admin.withdrawals') }}" class="action-form" style="justify-content: center;">
                                    @csrf
                                    <input type="hidden" name="request_id" value="{{ $row->id }}">
                                    
                                    <button type="submit" name="process_action" value="Approved" class="btn-approve" onclick="return confirm('{{ app()->getLocale() === 'sw' ? 'Je, unataka kuidhinisha ombi hili la fedha?' : 'Approve this withdrawal request?' }}');">
                                        {{ __('messages.btn_approve') }}
                                    </button>
                                    
                                    <button type="submit" name="process_action" value="Rejected" class="btn-reject" onclick="return confirm('{{ app()->getLocale() === 'sw' ? 'Je, una uhakika unataka kukataa ombi hili?' : 'Reject this withdrawal request?' }}');">
                                        {{ __('messages.btn_reject') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">
                                🎉 {{ app()->getLocale() === 'sw' ? 'Hakuna maombi ya kutoa fedha yanayosubiri kwa sasa.' : 'No withdrawal requests pending at the moment.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
