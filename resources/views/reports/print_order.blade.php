<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
            color: #000;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
        }
        
        .header-section {
            text-align: center;
            padding: 4px 6px;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-weight: bold;
        }
        
        .info-table td {
            border-bottom: 1px solid #000;
            padding: 4px 6px;
            margin: 0;
            vertical-align: middle;
        }
        
        .info-table .right-align {
            text-align: right;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .main-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        
        .main-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        
        .description-col {
            width: 25%;
            text-align: left;
        }
        
        .qty-col, .price-col {
            width: 8%;
            text-align: center;
        }
        
        .unit-col {
            width: 12%;
            text-align: center;
        }
        
        .total-row {
            background-color: #ffff99;
            font-weight: bold;
        }
        
        .subtotal-section {
            margin-top: 10px;
        }
        
        .subtotal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .subtotal-table td {
            border: 1px solid #000;
            padding: 4px 8px;
        }
        
        .subtotal-label {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: right;
            width: 70%;
        }
        
        .subtotal-amount {
            text-align: center;
            width: 30%;
            font-weight: bold;
        }
        
        .grand-total {
            background-color: #ffff99;
            font-size: 12px;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-delivered { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header-section">
            Solar Installation Materials
        </div>
        
        <!-- Information Table -->
        <table class="info-table">
            <tr>
                <td>Client Name: {{ $order->customer->customer_name ?? 'Romeo Remalante' }}</td>
                <td class="right-align">Address: {{ $order->customer->address ?? 'Taguig' }}</td>
            </tr>
            <tr>
                <td>DATE: {{ $order->created_at->format('M j,Y') }}</td>
                <td class="right-align">Package: {{ number_format($order->total_amount_price/1000, 2) }}kw {{ number_format($order->total_amount_price/1000, 0) }}k</td>
            </tr>
        </table>
        
        <!-- Main Materials Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th class="description-col">Description</th>
                    <th class="qty-col">QTY</th>
                    <th class="unit-col">Unit</th>
                    <th class="price-col">Unit Price</th>
                    <th class="price-col">Price</th>
                    <th class="description-col">Service Charge for Fee</th>
                    <th class="qty-col">QTY</th>
                    <th class="unit-col">Unit #</th>
                    <th class="price-col">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="description-col">{{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}</td>
                    <td class="qty-col">{{ $order->quantity_order }}</td>
                    <td class="unit-col">{{ $order->inventory_quantity->inventory->category->category_name ?? 'pcs' }}</td>
                    <td class="price-col">{{ number_format($order->inventory_quantity->price ?? 0, 0) }}</td>
                    <td class="price-col">{{ number_format($order->total_amount_price, 0) }}</td>
                    <td class="description-col">Service & Installation</td>
                    <td class="qty-col">1</td>
                    <td class="unit-col">lot</td>
                    <td class="price-col">0</td>
                </tr>
                
                <!-- Empty rows for spacing -->
                @for($i = 0; $i < 15; $i++)
                <tr>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
                @endfor
                
                <!-- Subtotal Rows -->
                <tr>
                    <td class="description-col"><strong>COMMISSION</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
                <tr>
                    <td class="description-col"><strong>GAS</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">1250</td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
                <tr>
                    <td class="description-col"><strong>FOOD</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">950</td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
                <tr>
                    <td class="description-col"><strong>TOLL</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">1620</td>
                    <td class="description-col"><strong>Total</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">0</td>
                </tr>
                <tr>
                    <td class="description-col"><strong>LABOR</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col">15000</td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
                <tr class="total-row">
                    <td class="description-col"><strong>Total</strong></td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                    <td class="price-col"><strong>{{ number_format($order->total_amount_price + 18820, 0) }}</strong></td>
                    <td class="description-col">&nbsp;</td>
                    <td class="qty-col">&nbsp;</td>
                    <td class="unit-col">&nbsp;</td>
                    <td class="price-col">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Subtotal Section -->
        <div class="subtotal-section">
            <table class="subtotal-table">
                <tr>
                    <td class="subtotal-label">&nbsp;</td>
                    <td class="subtotal-amount">{{ number_format($order->total_amount_price + 20000, 0) }}</td>
                </tr>
                <tr>
                    <td class="subtotal-label">&nbsp;</td>
                    <td class="subtotal-amount">&nbsp;</td>
                </tr>
                <tr>
                    <td class="subtotal-label">Grand Net Profit</td>
                    <td class="subtotal-amount grand-total">{{ number_format(($order->total_amount_price * 0.3), 0) }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Additional Information -->
        <div style="padding: 10px; border-top: 1px solid #000; font-size: 10px;">
            <div style="margin-bottom: 5px;">
                <strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->date_deliver)->format('M d, Y') }}
            </div>
            @if($order->reason)
            <div style="margin-bottom: 5px;">
                <strong>Notes:</strong> {{ $order->reason }}
            </div>
            @endif
            <div style="text-align: right; margin-top: 10px; font-size: 9px;">
                Generated on {{ $print_date }}
            </div>
        </div>
    </div>
</body>
</html>
