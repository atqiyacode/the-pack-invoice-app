<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice['invoice_number'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
        }

        .invoice-header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .client-info,
        .invoice-info {
            margin-bottom: 20px;
        }

        .client-info p,
        .invoice-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f5f5f5;
        }

        .total-summary {
            margin-top: 20px;
            text-align: right;
        }

        .total-summary p {
            font-size: 16px;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        <h1>Invoice</h1>
        <p>Invoice Number: {{ $invoice['invoice_number'] }}</p>
        <p>Invoice Date: {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('d M Y') }}</p>
    </div>

    <div class="client-info">
        <h3>Client Information</h3>
        <p><strong>Name:</strong> {{ $invoice['client_name'] }}</p>
        <p><strong>Address:</strong> {{ $invoice['client_address'] }}</p>
    </div>

    <div class="invoice-info">
        <h3>Remarks</h3>
        <p>{{ $invoice['remarks'] }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice['items'] as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['item_name'] }}</td>
                    <td>${{ number_format($item['item_price'], 2) }}</td>
                    <td>{{ $item['item_quantity'] }}</td>
                    <td>${{ number_format($item['item_amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-summary">
        <p><strong>Subtotal:</strong> ${{ number_format($invoice['subtotal'], 2) }}</p>
        <p>
            <strong>Discount ({{ number_format($invoice['discount'], 2) }}%):</strong>
            ${{ number_format($invoice['discount_amount'], 2) }}
        </p>
        <p><strong>GST ({{ number_format($invoice['gst'], 2) }}%):</strong>
            ${{ number_format($invoice['gst_amount'], 2) }}</p>
        <p><strong>Grand Total:</strong> ${{ number_format($invoice['grand_total'], 2) }}</p>
    </div>
</body>

</html>
