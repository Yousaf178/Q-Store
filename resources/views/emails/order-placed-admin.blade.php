<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received - {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #2563eb; color: #ffffff; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px 40px; color: #333333; line-height: 1.7; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #eff6ff; color: #1d4ed8; padding: 10px; text-align: left; border: 1px solid #bfdbfe; }
        td { padding: 10px; border: 1px solid #e5e7eb; }
        .total-row td { font-weight: bold; background-color: #eff6ff; }
        .info-box { background-color: #f9fafb; border-left: 4px solid #2563eb; padding: 12px 16px; margin-top: 20px; border-radius: 4px; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { background-color: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #888888; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🛒 New Order Received</h1>
        </div>
        <div class="body">
            <p>A new order has been placed on <strong>{{ config("app.name") }}</strong>.</p>

            <div class="info-box">
                <strong>Order Number:</strong> {{ $order->order_number }}<br>
                <strong>Customer Name:</strong> {{ $order->customer_name }}<br>
                <strong>Customer Email:</strong> {{ $order->customer_email }}<br>
                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
                <strong>Transaction ID:</strong> {{ $order->transaction_id }}<br>
                <strong>Shipping Address:</strong> {{ $order->shipping_address }}
            </div>

            <h3 style="margin-top:24px;">Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ url("/admin/orders/" . $order->id) }}" class="btn">View Order in Admin</a>
        </div>
        <div class="footer">
            &copy; {{ date("Y") }} {{ config("app.name") }}. All rights reserved.
        </div>
    </div>
</body>
</html>
