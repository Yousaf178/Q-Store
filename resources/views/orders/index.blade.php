@extends('layouts.app')

@section('title', 'My Orders - QShop')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-bag-check text-primary me-2"></i> My Order History</h2>
                <p class="text-muted mb-0">View your past purchases, payment statuses, and receipts.</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-shop me-1"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

@if($orders->count())
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date & Time</th>
                                <th>Items</th>
                                <th>Payment Method</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-end">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($order->items as $item)
                                                <li>{{ $item->quantity }}x {{ $item->product_name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            @if($order->payment_method === 'credit_card')
                                                <i class="bi bi-credit-card me-1"></i> Card
                                            @elseif($order->payment_method === 'paypal')
                                                <i class="bi bi-paypal me-1 text-info"></i> PayPal
                                            @else
                                                <i class="bi bi-bank me-1"></i> Bank
                                            @endif
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('order.success', $order) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-receipt me-1"></i> View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@else
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <div class="card shadow-sm border-0 p-5">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3 mb-2">No Orders Found</h4>
                <p class="text-muted mb-4">You haven't made any purchases yet. Explore our products to place your first order!</p>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-shop me-1"></i> Explore Products
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
