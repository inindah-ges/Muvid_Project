<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $selling->invoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 30px;
        }
        .invoice-info {
            text-align: right;
            margin-bottom: 30px;
        }
        .customer-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .totals {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $company['name'] }}</h2>
        <p>{{ $company['address'] }}</p>
        <p>Phone: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
    </div>

    <div class="invoice-info">
        <h3>INVOICE #{{ $selling->invoice }}</h3>
        <p>Date: {{ $selling->created_at->format('d M Y') }}</p>
        <p>Time: {{ $selling->created_at->format('H:i') }}</p>
    </div>

    <div class="customer-info">
        <h4>Bill To:</h4>
        <p>{{ $selling->user->name }}</p>
        <p>{{ $selling->user->email }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
                $totalDiscount = 0;
                $totalTax = 0;
            @endphp
            @foreach($selling->sellingDetails as $detail)
                @php
                    $itemSubtotal = $detail->quantity * $detail->unit_price;
                    $itemDiscount = $detail->discount ?? 0;
                    $itemTax = $detail->tax ? ($itemSubtotal - $itemDiscount) * ($detail->tax->rate / 100) : 0;
                    $subtotal += $itemSubtotal;
                    $totalDiscount += $itemDiscount;
                    $totalTax += $itemTax;
                @endphp
                <tr>
                    <td>
                        {{ $detail->product->name }}
                        <br>
                        <small>{{ $detail->product->category->name }}</small>
                    </td>
                    <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>
                        @if($detail->discount)
                            Rp {{ number_format($detail->discount, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($detail->tax)
                            {{ $detail->tax->rate }}%
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($itemSubtotal - $itemDiscount + $itemTax, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="totals">Subtotal:</td>
                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="totals">Total Discount:</td>
                <td>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="totals">Total Tax:</td>
                <td>Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="totals"><strong>Grand Total:</strong></td>
                <td><strong>Rp {{ number_format($selling->total_price, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Terms & Conditions:</p>
        <ol>
            <li>All prices are in Indonesian Rupiah (IDR)</li>
            <li>This is a computer generated invoice</li>
            <li>Thank you for your business!</li>
        </ol>
    </div>

    <div class="signature">
        <p>Authorized By</p>
        <br>
        <p>_________________</p>
    </div>
</body>
</html>
