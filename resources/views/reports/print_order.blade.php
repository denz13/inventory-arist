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
            padding: 10px;
            color: #000;
            background: white;
            font-size: 11px;
        }
        .client-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            font-size: 11px;
        }
        .client-info-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            text-align: left;
        }
        .main-content {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .left-section, .right-section {
            flex: 1;
        }
        .section-header {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 3px;
            border: 1px solid #000;
        }
        .materials-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .materials-table th,
        .materials-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: left;
        }
        .materials-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        .materials-table .qty-col {
            width: 40px;
            text-align: center;
        }
        .materials-table .price-col {
            width: 60px;
            text-align: right;
        }
        .materials-table .unit-price-col {
            width: 60px;
            text-align: right;
        }
        .summary-section {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .summary-left, .summary-right {
            flex: 1;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .summary-table td {
            border: 1px solid #000;
            padding: 2px 4px;
        }
        .summary-table .label-col {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .summary-table .value-col {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #ffff00 !important;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: end;
            font-size: 10px;
        }
        .footer-left {
            text-align: left;
        }
        .footer-center {
            text-align: center;
        }
        .footer-right {
            text-align: right;
        }
        .calculation {
            font-weight: bold;
            margin-top: 3px;
        }
        .grand-total {
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <table class="client-info-table">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 14px; font-weight: bold; border: 1px solid #000; padding: 8px;">
                Solar Installation Materials
            </td>
        </tr>
        <tr>
            <td><strong>Client Name: {{ $order->customer->customer_name ?? 'N/A' }}</strong></td>
            <td><strong>Address: {{ $order->customer->address ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td><strong>DATE: {{ $order->created_at->format('M j,Y') }}</strong></td>
            <td><strong>Package: {{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}</strong></td>
        </tr>
    </table>

    <!-- Main Table with Side by Side Layout -->
    <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin: 0;">
        <!-- Headers -->
        <tr style="background-color: #f0f0f0;">
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 25%;">Description</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 6%;">QTY</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 10%;">Unit Price</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 9%;">Price</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 25%;">Service Entrace for Rec Meter</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 6%;">QTY</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 10%;">Unit Price</th>
            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; width: 9%;">Price</th>
        </tr>
        
        <!-- Row 1 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px;">Bushing 1-3/4 3/4-1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">25</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 2 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">{{ $order->inventory_quantity->inventory->item_name ?? 'Phelp Dodge #12 White' }}</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">{{ number_format($order->inventory_quantity->price ?? 33, 0) }}</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">{{ number_format($order->total_amount_price ?? 0, 0) }}</td>
            <td style="border: 1px solid #000; padding: 2px;">Stainless Plug 1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">14</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Remaining rows with all items -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Phelp Dodge #14 White</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">20</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon 1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">37</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Phelp Dodge #14 Black</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">20</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon Adapter 1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">27</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Power Panel Box #4</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">165</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon 3/4</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">5</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">53</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">265</td>
        </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Metal clamp w screw tox</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">10</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">5</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">50</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon Adapter 3/4</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">33</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Summary section rows -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">COMMISSION</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">1250</td>
            <td style="border: 1px solid #000; padding: 2px;">Total</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">4750</td>
        </tr>
        
        <!-- Total Row (Yellow) -->
        <tr style="background-color: #ffff00;">
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Total</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">{{ number_format($order->total_amount_price ?? 157591, 0) }}</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Grand Net Profit -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Grand Net Profit</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">67659</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
    </table>

    <!-- Bottom Summary Section -->
    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
        <div style="text-align: left;">
            <strong>{{ $order->customer->customer_name ?? 'Romeo Remalante' }}</strong>
        </div>
        <div style="text-align: center;">
            <div><strong>{{ number_format($order->total_amount_price ?? 230000, 0) }}-{{ number_format(($order->total_amount_price ?? 230000) * 0.7, 0) }} &nbsp;&nbsp; {{ number_format(($order->total_amount_price ?? 230000) / 21000, 1) }}kw</strong></div>
            <div style="margin-top: 5px;"><strong>67659</strong></div>
        </div>
        <div style="text-align: right;">
        </div>
    </div>
</body>
</html>
