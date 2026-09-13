@extends('layouts.admin')

@section('title', ($text['title'] ?? 'Ripoti za Mauzo') . ' - TanzaMart Admin')

@section('styles')
<style>
    .report-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
    }
    .filters-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .filter-btn {
        text-decoration: none;
        background: #f1f5f9;
        color: #475569;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        transition: 0.2s;
        border: 1px solid #e2e8f0;
    }
    .filter-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .filter-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .chart-box {
        position: relative;
        height: 380px;
        width: 100%;
        background: #f8fafc;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 28px;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
    }
    .report-table th {
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
    .report-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .report-table tr:last-child td { border-bottom: none; }
    .report-table tr:hover { background-color: #f8fafc; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
        📊 {{ $text['heading'] ?? __('messages.sales_reports') }}
    </h1>
    <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
        {{ $text['desc'] ?? (app()->getLocale() === 'sw' ? 'Uchambuzi wa kina wa mwenendo wa mauzo na oda kwenye jukwaa la TanzaMart.' : 'Comprehensive analytics of sales and customer orders across the platform.') }}
    </p>
</div>

<div class="report-card">
    <div class="filters-bar">
        <a href="?filter=daily" class="filter-btn {{ ($filter == 'daily') ? 'active' : '' }}">{{ $text['filter_daily'] }}</a>
        <a href="?filter=weekly" class="filter-btn {{ ($filter == 'weekly') ? 'active' : '' }}">{{ $text['filter_weekly'] }}</a>
        <a href="?filter=monthly" class="filter-btn {{ ($filter == 'monthly') ? 'active' : '' }}">{{ $text['filter_monthly'] }}</a>
        <a href="?filter=yearly" class="filter-btn {{ ($filter == 'yearly') ? 'active' : '' }}">{{ $text['filter_yearly'] }}</a>
    </div>

    <div class="chart-box">
        <canvas id="salesChart"></canvas>
    </div>

    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 10px;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $title_col }}</th>
                    <th>{{ $text['col_orders'] }}</th>
                    <th>{{ $text['col_sales'] }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report_data as $row)
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">{{ $row['period'] }}</td>
                        <td>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-weight: 600; font-size: 0.75rem;">
                                {{ number_format($row['total_orders']) }}
                            </span>
                        </td>
                        <td style="color: #16a34a; font-weight: 700;">TZS {{ number_format($row['total_sales'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 28px; color: #64748b;">
                            {{ $text['no_data'] }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($periods),
            datasets: [{
                label: '{{ $text['col_sales'] }} (TZS)',
                data: @json($sales_data),
                backgroundColor: 'rgba(37, 99, 235, 0.65)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 2,
                borderRadius: 6,
                barThickness: @json(count($periods) === 1 ? 220 : null)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '{{ $text['chart_title'] }} (' + '{{ strtoupper($filter) }}' + ')'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'TZS ' + Number(value).toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
