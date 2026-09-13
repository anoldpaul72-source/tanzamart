<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risiti ya Oda #{{ $order->id }} - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            background: #f4f7f6;
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-print {
            background: #00bcd4;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 6px rgba(0, 188, 212, 0.3);
            transition: background 0.2s ease;
        }

        .btn-print:hover {
            background: #00acc1;
        }

        .receipt-card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 28px 25px;
            border-radius: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            color: #111;
        }

        .store-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .store-header h2 {
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .store-header p {
            font-size: 12px;
            color: #555;
            line-height: 1.4;
        }

        .refund-badge {
            background: #f8d7da;
            color: #721c24;
            border: 1px dashed #f5c6cb;
            text-align: center;
            padding: 8px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-section {
            font-size: 13px;
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
        }

        .items-table td {
            padding: 6px 0;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            border-top: 2px dashed #333;
            padding-top: 10px;
            font-size: 14.5px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .footer-note {
            text-align: center;
            font-size: 11.5px;
            color: #666;
            margin-top: 15px;
            line-height: 1.6;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Chapisha Risiti / Hifadhi PDF
        </button>
    </div>

    <div class="receipt-card">
        <div class="store-header">
            <h2>TANZAMART</h2>
            <p>Online Marketplace</p>
            <p>Simu: 0621 530 804</p>
        </div>

        @php
            $isRefunded = in_array(strtolower($order->payment_status ?? ''), ['refunded']) || in_array(strtolower($order->status ?? ''), ['refunded']);
        @endphp

        @if($isRefunded)
            <div class="refund-badge">
                ⚠️ PESA IMERUDISHWA KWA MTEJA (REFUNDED)
            </div>
        @endif

        <div class="info-section">
            <div class="info-row">
                <span>Namba ya Risiti:</span>
                <strong>#{{ $order->id }}</strong>
            </div>
            <div class="info-row">
                <span>Tarehe:</span>
                <span>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Mteja:</span>
                <span>{{ $order->name ?? 'Mteja' }}</span>
            </div>
            <div class="info-row">
                <span>Simu:</span>
                <span>{{ $order->phone ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span>Jiji:</span>
                <span>{{ $order->city ?? 'Dar Es Salaam' }}</span>
            </div>
            <div class="info-row">
                <span>Namba ya Muamala:</span>
                <span>{{ $order->transaction_id ?? $order->order_number }}</span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Bidhaa</th>
                    <th class="text-right">Idadi</th>
                    <th class="text-right">Bei (Tsh)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    @php
                        $subtotal = $item->subtotal ?: ($item->price * $item->quantity);
                        $name = $item->product ? $item->product->name : ($item->name ?? 'Bidhaa');
                    @endphp
                    <tr>
                        <td>{{ $name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($subtotal) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Hakuna bidhaa</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <span>{{ $isRefunded ? 'HALI YA PESA:' : 'JUMLA ILIYOLIPWA:' }}</span>
            <span>Tsh {{ number_format($order->total) }}</span>
        </div>

        <div class="footer-note">
            <p>Asante kwa kununua kupitia TanzaMart!</p>
            <p>{{ $isRefunded ? '*** PESA IMERUDISHWA (REFUNDED) ***' : '*** Oda Imelipiwa ***' }}</p>
        </div>
    </div>

</body>
</html>
