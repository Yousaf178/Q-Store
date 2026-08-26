@extends('layouts.app')

@section('title', 'Shopping Cart - QShop')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-cart3 me-2 text-primary"></i> Shopping Cart</h2>
                <p class="text-muted mb-0">Review items in your cart before proceeding to online checkout.</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

@if(!empty($cart) && count($cart) > 0)
    <div class="row g-4">
        <!-- Cart Items Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Cart Items ({{ count($cart) }})</h5>
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to empty your cart?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Empty Cart
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="width: 180px;">Quantity</th>
                                <th>Subtotal</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img 
                                                src="{{ $item['image_url'] ?? "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='60' height='60'><rect fill='%23f1f3f5' width='60' height='60'/><text fill='%23adb5bd' font-size='12' x='50%' y='50%' text-anchor='middle' dominant-baseline='middle'>Item</text></svg>" }}" 
                                                alt="{{ $item['name'] }}" 
                                                class="rounded border" 
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                            >
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                                <span class="badge bg-secondary-subtle text-secondary small">
                                                    {{ $item['category'] }}
                                                </span>
                                                <div class="small text-muted">Stock: {{ $item['max_stock'] }} units</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        ${{ number_format($item['price'], 2) }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            <input 
                                                type="number" 
                                                name="quantity" 
                                                value="{{ $item['quantity'] }}" 
                                                min="1" 
                                                max="{{ $item['max_stock'] }}" 
                                                class="form-control form-control-sm text-center" 
                                                style="width: 70px;"
                                                required
                                            >
                                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Update Quantity">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="fw-bold text-success">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Remove Item">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary & Checkout Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i> Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery / Shipping:</span>
                        <span class="text-success fw-semibold">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 fw-bold">Total Amount:</span>
                        <span class="fs-4 fw-bold text-success">${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout') }}" class="btn btn-success btn-lg fw-bold">
                            <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            Add More Items
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 py-3 text-center small text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i> Secure 256-bit Encrypted Checkout
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Empty Cart State -->
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <div class="card shadow-sm border-0 p-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3 mb-2">Your Shopping Cart is Empty</h4>
                <p class="text-muted mb-4">You haven't added any products to your cart yet.</p>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-shop me-1"></i> Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
