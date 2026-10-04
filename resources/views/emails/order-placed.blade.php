<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #16a34a; color: #ffffff; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px 40px; color: #333333; line-height: 1.7; }
        .body h2 { color: #16a34a; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #f0fdf4; color: #15803d; padding: 10px; text-align: left; border: 1px solid #bbf7d0; }
        td { padding: 10px; border: 1px solid #e5e7eb; }
        .total-row td { font-weight: bold; background-color: #f0fdf4; }
        .info-box { background-color: #f9fafb; border-left: 4px solid #16a34a; padding: 12px 16px; margin-top: 20px; border-radius: 4px; }
        .footer { background-color: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #888888; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🎉 Order Confirmed!</h1>
        </div>
        <div class="body">
            <h2>Hi {{ $order->customer_name }},</h2>
            <p>Your order has been placed successfully. Here are your order details:</p>

            <div class="info-box">
                <strong>Order Number:</strong> {{ $order->order_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->transaction_id }}<br>
                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
                <strong>Shipping Address:</strong> {{ $order->shipping_address }}
            </div>

            <h3 style="margin-top:24px;">Items Ordered</h3>
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

            <p style="margin-top:24px;">Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            &copy; {{ date("Y") }} {{ config("app.name") }}. All rights reserved.
        </div>
    </div>
</body>
</html>
