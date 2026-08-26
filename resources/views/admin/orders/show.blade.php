@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Admin Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Back Navigation -->
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to All Orders
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-dark btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Invoice
                </button>
                @if($order->user)
                    <a href="{{ route('admin.orders.index', ['user_id' => $order->user_id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person me-1"></i> View All Orders by This Customer
                    </a>
                @endif
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-warning text-dark mb-1">STORE INVOICE</span>
                    <h3 class="mb-0 fw-bold">Order #{{ $order->order_number }}</h3>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-check2-circle me-1"></i> {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Metadata Grid -->
                <div class="row g-3 mb-4 pb-3 border-bottom">
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Order Placed Date</small>
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
                                <i class="bi bi-credit-card text-primary me-1"></i> Credit Card
                            @elseif($order->payment_method === 'paypal')
                                <i class="bi bi-paypal text-info me-1"></i> PayPal
                            @else
                                <i class="bi bi-bank me-1"></i> Bank Transfer
                            @endif
                        </strong>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted text-uppercase d-block fw-semibold">Grand Total</small>
                        <strong class="text-success fs-5">${{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>

                <!-- Customer & Delivery Information -->
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2"><i class="bi bi-person-badge text-primary me-1"></i> Customer Account Details</h6>
                        <p class="mb-1 text-dark">
                            <strong>{{ $order->customer_name }}</strong>
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-envelope me-1"></i> {{ $order->customer_email }}
                        </p>
                        @if($order->user)
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-person me-1"></i> Account Role: <span class="badge {{ $order->user->isAdmin() ? 'bg-danger' : 'bg-primary' }}">{{ $order->user->role }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2"><i class="bi bi-truck text-success me-1"></i> Shipping & Delivery Address</h6>
                        <p class="mb-0 text-muted bg-light p-3 rounded">
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>

                <!-- Purchased Items Table -->
                <h6 class="fw-bold mb-3"><i class="bi bi-basket text-primary me-1"></i> Order Line Items</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Item</th>
                                <th class="text-center" style="width: 120px;">Unit Price</th>
                                <th class="text-center" style="width: 90px;">Quantity</th>
                                <th class="text-end" style="width: 140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                                @if($item->product && $item->product->category)
                                                    <span class="badge bg-secondary-subtle text-secondary small">
                                                        {{ $item->product->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-success">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Amount Paid:</td>
                                <td class="text-end fw-bold text-success fs-5">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer notice -->
                <div class="alert alert-light border small text-muted text-center mb-0">
                    <i class="bi bi-shield-check text-success me-1"></i> Verified Transaction recorded in QShop database.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
