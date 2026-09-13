@extends('layouts.admin')

@section('title', __('messages.all_orders') . ' - TanzaMart Admin')

@section('styles')
<style>
    .order-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .order-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
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
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-delivered { background: #dcfce7; color: #15803d; }
    .badge-processing { background: #e0f2fe; color: #0369a1; }
    .badge-refunded { background: #fee2e2; color: #b91c1c; }
    .badge-disputed { background: #fce7f3; color: #be185d; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
        📑 {{ __('messages.all_orders') }}
    </h1>
    <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
        {{ app()->getLocale() === 'sw' 
            ? 'Orodha ya oda zote zilizowekwa na wateja kwenye mfumo wa TanzaMart.' 
            : 'Complete record of all customer orders placed across the TanzaMart platform.' }}
    </p>
</div>

<div class="order-table-card">
    <div style="overflow-x: auto;">
        <table class="order-table">
            <thead>
                <tr>
                    <th style="width: 10%;">{{ __('messages.order_number') }}</th>
                    <th style="width: 25%;">{{ __('messages.customer') }}</th>
                    <th style="width: 20%;">{{ __('messages.total_amount') }}</th>
                    <th style="width: 20%;">{{ __('messages.status') }}</th>
                    <th style="width: 25%;">{{ __('messages.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $row)
                    @php
                        $st = strtolower(trim($row->status));
                        $badgeClass = 'badge-pending';
                        $statusText = __('messages.pending');

                        if (in_array($st, ['delivered', 'received', 'completed'])) {
                            $badgeClass = 'badge-delivered';
                            $statusText = __('messages.delivered');
                        } elseif (in_array($st, ['processing', 'dispatched'])) {
                            $badgeClass = 'badge-processing';
                            $statusText = __('messages.processing');
                        } elseif (in_array($st, ['refunded', 'cancelled'])) {
                            $badgeClass = 'badge-refunded';
                            $statusText = __('messages.refunded');
                        } elseif (in_array($st, ['disputed', 'dispute'])) {
                            $badgeClass = 'badge-disputed';
                            $statusText = __('messages.disputed');
                        }
                    @endphp
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">#{{ $row->id }}</td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">{{ $row->name }}</div>
                            @if(!empty($row->phone))
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $row->phone }}</div>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #0f172a;">TZS {{ number_format($row->total, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $badgeClass }}">
                                ● {{ $statusText }}
                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 0.8125rem;">
                            {{ $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 36px;">
                            {{ app()->getLocale() === 'sw' ? 'Hakuna oda zilizopatikana.' : 'No orders found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
