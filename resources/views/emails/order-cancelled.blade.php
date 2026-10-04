<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled - {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #dc2626; color: #ffffff; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px 40px; color: #333333; line-height: 1.7; }
        .body h2 { color: #dc2626; }
        .info-box { background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 12px 16px; margin-top: 20px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #fef2f2; color: #b91c1c; padding: 10px; text-align: left; border: 1px solid #fecaca; }
        td { padding: 10px; border: 1px solid #e5e7eb; }
        .total-row td { font-weight: bold; background-color: #fef2f2; }
        .footer { background-color: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #888888; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>❌ Order Cancelled</h1>
        </div>
        <div class="body">
            <h2>Hi {{ $order->customer_name }},</h2>
            <p>We are sorry to let you know that your order has been <strong>cancelled</strong>.</p>

            <div class="info-box">
                <strong>Order Number:</strong> {{ $order->order_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->transaction_id }}<br>
                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
            </div>

            <h3 style="margin-top:24px;">Cancelled Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <p style="margin-top:24px;">If you have any questions, please contact our support team.</p>
        </div>
        <div class="footer">
            &copy; {{ date("Y") }} {{ config("app.name") }}. All rights reserved.
        </div>
    </div>
</body>
</html>
