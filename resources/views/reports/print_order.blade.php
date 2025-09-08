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
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            color: #666;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
            margin-right: 20px;
        }
        .info-section:last-child {
            margin-right: 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .info-value {
            flex: 1;
        }
        .order-details {
            margin-top: 30px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .details-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-delivered { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company_name }}</div>
        <div class="document-title">ORDER RECEIPT</div>
    </div>

    <div class="order-info">
        <div class="info-section">
            <div class="section-title">Order Information</div>
            <div class="info-row">
                <div class="info-label">Order ID:</div>
                <div class="info-value">#{{ $order->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Order Date:</div>
                <div class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Customer Information</div>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $order->customer->customer_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $order->customer->address ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="order-details">
        <div class="section-title">Order Details</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}</td>
                    <td>{{ $order->inventory_quantity->inventory->category->category_name ?? 'N/A' }}</td>
                    <td>{{ $order->quantity_order }}</td>
                    <td>₱{{ number_format($order->inventory_quantity->price ?? 0, 2) }}</td>
                    <td>₱{{ number_format($order->total_amount_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-amount">
                Total Amount: ₱{{ number_format($order->total_amount_price, 2) }}
            </div>
        </div>

        <div style="margin-top: 20px;">
            <div class="info-row">
                <div class="info-label">Delivery Date:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($order->date_deliver)->format('M d, Y') }}</div>
            </div>
            @if($order->reason)
            <div class="info-row">
                <div class="info-label">Notes:</div>
                <div class="info-value">{{ $order->reason }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ $print_date }}</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
