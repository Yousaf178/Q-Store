@extends('layouts.app')

@section('title', 'Customer Orders - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-receipt-cutoff text-success me-2"></i> Orders Management</h2>
                <p class="text-muted mb-0">Monitor and inspect all purchases, transactions, and customer orders.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people me-1"></i> View Users
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@if(isset($selectedUser))
    <!-- Active User Filter Alert -->
    <div class="alert alert-primary d-flex justify-content-between align-items-center mb-4 shadow-sm" role="alert">
        <div>
            <i class="bi bi-person-fill fs-5 me-2"></i>
            Filtering orders placed by: <strong>{{ $selectedUser->name }}</strong> ({{ $selectedUser->email }})
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-x-circle me-1"></i> View All Orders
        </a>
    </div>
@endif

<!-- Summary Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Total Orders</small>
                <h3 class="fw-bold mb-0 text-dark">{{ $totalOrdersCount }}</h3>
            </div>
            <div class="fs-1 text-primary opacity-75">
                <i class="bi bi-bag-check-fill"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Total Revenue</small>
                <h3 class="fw-bold mb-0 text-success">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
            <div class="fs-1 text-success opacity-75">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Card Payments</small>
                <h3 class="fw-bold mb-0 text-info">{{ $cardOrdersCount }}</h3>
            </div>
            <div class="fs-1 text-info opacity-75">
                <i class="bi bi-credit-card-2-front-fill"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">PayPal Payments</small>
                <h3 class="fw-bold mb-0 text-warning">{{ $paypalOrdersCount }}</h3>
            </div>
            <div class="fs-1 text-warning opacity-75">
                <i class="bi bi-paypal"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Card -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            @if(request('user_id'))
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif

            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by Order #, Customer Name, Email, or Transaction ID..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="payment_method" class="form-select">
                        <option value="">All Payment Methods</option>
                        <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit / Debit Card</option>
                        <option value="paypal" {{ request('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request('search') || request('payment_method') || request('user_id'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
@if($orders->count())
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Purchased Items</th>
                        <th>Payment</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong class="text-dark font-monospace">{{ $order->order_number }}</strong>
                                <div class="small text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                <div class="small text-muted">{{ $order->customer_email }}</div>
                                @if($order->user)
                                    <a href="{{ route('admin.orders.index', ['user_id' => $order->user_id]) }}" class="badge bg-light text-primary border text-decoration-none mt-1">
                                        <i class="bi bi-funnel me-1"></i> User #{{ $order->user_id }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($order->items as $item)
                                        <div class="d-flex align-items-center gap-2 small">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="rounded border" style="width: 25px; height: 25px; object-fit: cover;">
                                            @endif
                                            <span><strong>{{ $item->quantity }}x</strong> {{ Str::limit($item->product_name, 25) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    @if($order->payment_method === 'credit_card')
                                        <i class="bi bi-credit-card text-primary me-1"></i> Card
                                    @elseif($order->payment_method === 'paypal')
                                        <i class="bi bi-paypal text-info me-1"></i> PayPal
                                    @else
                                        <i class="bi bi-bank me-1"></i> Bank
                                    @endif
                                </span>
                                @if($order->transaction_id)
                                    <div class="small text-muted font-monospace" style="font-size: 0.72rem;">
                                        {{ Str::limit($order->transaction_id, 16) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success fs-6">${{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success text-uppercase">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                    <i class="bi bi-eye me-1"></i> View Invoice
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-3">
            {{ $orders->links() }}
        </div>
    </div>
@else
    <div class="card shadow-sm border-0 text-center py-5">
        <div class="card-body">
            <i class="bi bi-inbox text-muted" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold mt-3 mb-1">No orders found</h5>
            <p class="text-muted mb-0">No customer orders matched your filter or search query.</p>
        </div>
    </div>
@endif
@endsection
