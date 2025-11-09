<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders for {{ $deliveryDate === 'no-date' ? 'No Date Set' : \Carbon\Carbon::parse($deliveryDate)->format('M d, Y') }}</title>
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
        .delivery-date-header {
            background-color: #f0f0f0;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 12px;
            border: 1px solid #000;
        }
        .summary-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .summary-item {
            text-align: center;
            border: 1px solid #000;
            padding: 6px;
            flex: 1;
            margin: 0 2px;
        }
        .summary-label {
            font-size: 9px;
            margin-bottom: 2px;
            font-weight: bold;
        }
        .summary-value {
            font-size: 11px;
            font-weight: bold;
        }
        .customer-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .customer-header {
            background-color: #f0f0f0;
            padding: 6px;
            border: 1px solid #000;
            border-bottom: none;
        }
        .customer-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .customer-address {
            font-size: 10px;
        }
        .customer-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .main-content {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
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
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .orders-table th,
        .orders-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: left;
        }
        .orders-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        .orders-table .qty-col {
            width: 40px;
            text-align: center;
        }
        .orders-table .price-col {
            width: 60px;
            text-align: right;
        }
        .orders-table .unit-price-col {
            width: 60px;
            text-align: right;
        }
        .summary-section {
            display: flex;
            gap: 15px;
            margin-top: 12px;
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
            <td><strong>Client Name: Multiple Customers</strong></td>
            <td><strong>Address: Various Locations</strong></td>
        </tr>
        <tr>
            <td><strong>DATE: {{ $deliveryDate === 'no-date' ? 'No Date Set' : \Carbon\Carbon::parse($deliveryDate)->format('M j,Y') }}</strong></td>
            <td><strong>Package: {{ number_format($totalAmount / 1000, 2) }}kw {{ number_format($totalAmount, 0) }}k</strong></td>
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
            <td style="border: 1px solid #000; padding: 2px;">Phelp Dodge #12 White</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">33</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Stainless Plug 1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">14</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 3 -->
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
        
        <!-- Row 4 -->
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
        
        <!-- Row 5 -->
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
        
        <!-- Row 6 -->
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
        
        <!-- Row 7 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Grouding rod (galvanize)</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">250</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon Adapter 1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">51</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">51</td>
        </tr>
        
        <!-- Row 8 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Pvc orane 1/2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">90</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon 1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">74</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 9 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Cable Tray plastic</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">260</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">260</td>
            <td style="border: 1px solid #000; padding: 2px;">Flexcon 1 1/4</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">95</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 10 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Distribution Box 13 Metal</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">600</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">ATS</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">3000</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 11 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Distribution Box 16 Metal</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">700</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;">CABINET BOX</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">500</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 12 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Distribution Box 18 Metal</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">800</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">800</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 13 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Ac SPD</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">1</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">400</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">400</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Row 14 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Dc SPD</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">800</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">1600</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                        </tr>
        
        <!-- Row 15 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">DC 20A</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">400</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">800</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                        </tr>
        
        <!-- Row 16 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">20A</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">200</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                        </tr>
        
        <!-- Row 17 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">32A</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">200</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                        </tr>
        
        <!-- Row 18 -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">63A</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;">2</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">240</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">480</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                        </tr>
        
        <!-- Commission Section -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">COMMISSION</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">1250</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                    </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">GAS</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">950</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                    </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">FOOD</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">1620</td>
            <td style="border: 1px solid #000; padding: 2px;">Total</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">4750</td>
                    </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">TOLL</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">15000</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                    </tr>
        
        <tr>
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">LABOR</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
        </tr>
        
        <!-- Total Row (Yellow) -->
        <tr style="background-color: #ffff00;">
            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Total</td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">157591</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">0</td>
                    </tr>
        
        <!-- Blank Row -->
        <tr>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: center;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;"></td>
            <td style="border: 1px solid #000; padding: 2px; text-align: right;">162341</td>
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
            <strong>Romeo Remalante</strong>
        </div>
        <div style="text-align: center;">
            <div><strong>230,000-162341 &nbsp;&nbsp; 10.9kw</strong></div>
            <div style="margin-top: 5px;"><strong>67659</strong></div>
            </div>
        <div style="text-align: right;">
        </div>
    </div>
</body>
</html>
