<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->order_number }} — {{ config('app.name') }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #000;
        }
        body {
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .actions-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
        }
        .btn-print:hover {
            background: #4338ca;
        }
        .btn-back {
            background: #e2e8f0;
            color: #1e293b;
        }
        .btn-back:hover {
            background: #cbd5e1;
        }
        .receipt-container {
            width: 80mm;
            max-width: 100%;
            background: #fff;
            padding: 16px 12px;
            border: 1px dashed #cbd5e1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            font-size: 12px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .store-name {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .store-sub {
            font-size: 10px;
            color: #333;
            margin-bottom: 2px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .double-divider {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 11px;
        }
        table.items-table th {
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            font-weight: bold;
        }
        table.items-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 900;
        }
        .footer-note {
            font-size: 10px;
            margin-top: 10px;
            color: #222;
            line-height: 1.3;
        }
        .barcode-box {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 4px;
            border: 1px solid #444;
            padding: 4px 0;
            background: #fafafa;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .actions-bar {
                display: none !important;
            }
            .receipt-container {
                width: 100%;
                max-width: 80mm;
                border: none;
                box-shadow: none;
                padding: 4mm;
            }
            @page {
                size: auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button onclick="window.print()" class="btn btn-print">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Print Receipt (Ctrl+P)
        </button>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-back">
            ← Back to Counter POS
        </a>
    </div>

    <div class="receipt-container" id="receipt">
        <div class="text-center">
            <div class="store-name">{{ config('app.name') }}</div>
            <div class="store-sub">{{ config('app.store.address') }}</div>
            <div class="store-sub">Tel: {{ config('app.store.phone') }} | WA: {{ config('app.store.whatsapp') }}</div>
        </div>

        <div class="divider"></div>

        <div class="meta-row">
            <span>Bill #: <strong>{{ $order->order_number }}</strong></span>
            <span>{{ $order->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="meta-row">
            <span>Cashier: {{ $order->cashier?->name ?? 'Staff' }}</span>
            <span>{{ $order->created_at->format('h:i A') }}</span>
        </div>
        <div class="meta-row">
            <span>Customer: {{ $order->customer_name }}</span>
            <span>{{ $order->customer_phone && $order->customer_phone !== 'Counter Sale' ? $order->customer_phone : '' }}</span>
        </div>

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-left" style="width: 50%;">ITEM</th>
                    <th class="text-center" style="width: 15%;">QTY</th>
                    <th class="text-right" style="width: 15%;">RATE</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="text-left font-bold">{{ $item->product_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->product_price) }}</td>
                        <td class="text-right font-bold">{{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rs {{ number_format($order->subtotal) }}</span>
        </div>

        @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>- Rs {{ number_format($order->discount_amount) }}</span>
            </div>
        @endif

        <div class="double-divider"></div>

        <div class="total-row grand-total">
            <span>NET TOTAL:</span>
            <span>Rs {{ number_format($order->total) }}</span>
        </div>

        <div class="double-divider"></div>

        <div class="total-row">
            <span>Payment Method:</span>
            <span class="font-bold" style="text-transform: uppercase;">{{ $order->payment_method }}</span>
        </div>

        @if($order->payment_reference)
            <div class="total-row">
                <span>Ref / TID:</span>
                <span>{{ $order->payment_reference }}</span>
            </div>
        @endif

        <div class="total-row">
            <span>Amount Paid:</span>
            <span>Rs {{ number_format($order->paid_amount) }}</span>
        </div>

        <div class="total-row font-bold">
            <span>Change Returned:</span>
            <span>Rs {{ number_format($order->change_amount) }}</span>
        </div>

        <div class="barcode-box">
            *{{ $order->order_number }}*
        </div>

        <div class="footer-note text-center">
            <p><strong>Thank you for shopping with us!</strong></p>
            <p>Exchange possible within 3 days with this slip.</p>
            <p style="margin-top: 4px; font-size: 9px; color: #666;">Software: Local Retail POS</p>
        </div>
    </div>

</body>
</html>
