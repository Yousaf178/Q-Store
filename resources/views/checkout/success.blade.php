@extends('layouts.app')

@section('title', 'Order Confirmation - #' . $order->order_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Success Banner -->
        <div class="text-center mb-4">
            <div class="display-4 text-success mb-2">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h2 class="fw-bold">Payment Successful & Order Placed!</h2>
            <p class="text-muted">Thank you for your purchase, {{ $order->customer_name }}. A confirmation email has been dispatched.</p>
        </div>

        <!-- Receipt Card -->
        <div class="card shadow border-0 mb-4" id="printableReceipt">
            <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-warning text-dark mb-1">OFFICIAL RECEIPT</span>
                    <h4 class="mb-0 fw-bold">Order #{{ $order->order_number }}</h4>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-check2-circle me-1"></i> {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Order Metadata Grid -->
                <div class="row g-3 mb-4 pb-3 border-bottom">
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Order Date</small>
                        <strong>{{ $order->created_at->format('d M Y, h:i A') }}</strong>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Transaction ID</small>
                        <strong class="text-primary font-monospace">{{ $order->transaction_id ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Payment Method</small>
                        <strong class="text-capitalize">
                            @if($order->payment_method === 'credit_card')
                                <i class="bi bi-credit-card me-1"></i> Credit Card
                            @elseif($order->payment_method === 'paypal')
                                <i class="bi bi-paypal me-1"></i> PayPal
                            @else
                                <i class="bi bi-bank me-1"></i> Bank Transfer
                            @endif
                        </strong>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Total Paid</small>
                        <strong class="text-success fs-5">${{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>

                <!-- Customer & Shipping -->
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2"><i class="bi bi-person me-1"></i> Customer Information</h6>
                        <p class="mb-0 text-muted">
                            <strong>{{ $order->customer_name }}</strong><br>
                            {{ $order->customer_email }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2"><i class="bi bi-truck me-1"></i> Shipping Address</h6>
                        <p class="mb-0 text-muted">
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>

                <!-- Purchased Items Table -->
                <h6 class="fw-bold mb-3"><i class="bi bi-basket me-1"></i> Purchased Items</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item Description</th>
                                <th class="text-center" style="width: 100px;">Price</th>
                                <th class="text-center" style="width: 80px;">Qty</th>
                                <th class="text-end" style="width: 120px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-semibold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total Paid:</td>
                                <td class="text-end fw-bold text-success fs-5">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Notice -->
                <div class="alert alert-light border small text-muted text-center mb-0">
                    <i class="bi bi-shield-check text-success me-1"></i> This order was processed and verified via QShop Secure Payment Gateway.
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-outline-dark" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Receipt
                </button>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-check me-1"></i> My Orders
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-success">
                        <i class="bi bi-shop me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
