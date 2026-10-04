@extends('layouts.app')

@section('title', $product->name . ' - Product Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Back Navigation -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Products
            </a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-cart3 me-1"></i> View Cart
            </a>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="row g-0">
                <!-- Product Picture Column -->
                <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4 border-end">
                    <img 
                        src="{{ $product->image_url }}" 
                        alt="{{ $product->name }}" 
                        class="img-fluid rounded shadow-sm w-100" 
                        style="max-height: 380px; object-fit: cover;"
                    >
                </div>

                <!-- Product Details Column -->
                <div class="col-md-7 p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        <span class="badge {{ $product->quantity > 0 ? 'bg-success fs-6' : 'bg-danger fs-6' }} px-3 py-1">
                            <i class="bi {{ $product->quantity > 0 ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                            {{ $product->quantity > 0 ? 'In Stock (' . $product->quantity . ' left)' : 'Out of Stock' }}
                        </span>
                    </div>

                    <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>

                    <div class="mb-3">
                        <span class="text-success fw-bold display-6">${{ number_format($product->price, 2) }}</span>
                        <span class="text-muted small">/ unit</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Description</h6>
                        <p class="text-secondary bg-light p-3 rounded-3 mb-0">
                            {{ $product->description ?: 'No detailed description provided for this item.' }}
                        </p>
                    </div>

                    <div class="mt-auto pt-3 border-top">
                        @if($product->quantity > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" id="productActionForm">
                                @csrf
                                
                                <div class="row align-items-end g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="quantity" class="form-label fw-bold small">Select Quantity</label>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary" type="button" onclick="adjustDetailQty(-1)">-</button>
                                            <input 
                                                type="number" 
                                                name="quantity" 
                                                id="detail_quantity" 
                                                class="form-control text-center fw-bold" 
                                                value="1" 
                                                min="1" 
                                                max="{{ $product->quantity }}"
                                                oninput="calcDetailTotal()"
                                                required
                                            >
                                            <button class="btn btn-outline-secondary" type="button" onclick="adjustDetailQty(1)">+</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="p-2 bg-light rounded text-center">
                                            <div class="text-muted small">Subtotal:</div>
                                            <div class="fs-4 fw-bold text-success" id="detail_total_display">${{ number_format($product->price, 2) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-outline-primary btn-lg flex-grow-1 fw-bold">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="btn btn-success btn-lg flex-grow-1 fw-bold">
                                        <i class="bi bi-credit-card me-1"></i> Buy Now
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Item Out of Stock</strong>
                                    <p class="mb-0 small">This item is currently unavailable for purchase. Please check back later.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="row justify-content-center mt-5">
    <div class="col-lg-10">
        <h4 class="fw-bold mb-4">You might also like</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 product-card overflow-hidden">
                        <a href="{{ route('products.details', $relatedProduct) }}" class="d-block bg-light text-center border-bottom">
                            <img 
                                src="{{ $relatedProduct->image_url }}" 
                                alt="{{ $relatedProduct->name }}" 
                                class="w-100" 
                                style="height: 150px; object-fit: cover;"
                            >
                        </a>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="mb-1 text-muted small">
                                {{ $relatedProduct->brand ?? $relatedProduct->category->name }}
                            </div>
                            <h6 class="card-title fw-bold mb-2">
                                <a href="{{ route('products.details', $relatedProduct) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($relatedProduct->name, 40) }}
                                </a>
                            </h6>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">${{ number_format($relatedProduct->price, 2) }}</span>
                                <a href="{{ route('products.details', $relatedProduct) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
    const unitPrice = {{ $product->price }};
    const maxStock = {{ $product->quantity }};

    function adjustDetailQty(delta) {
        const input = document.getElementById('detail_quantity');
        let current = parseInt(input.value) || 1;
        let next = current + delta;
        if (next >= 1 && next <= maxStock) {
            input.value = next;
            calcDetailTotal();
        }
    }

    function calcDetailTotal() {
        const input = document.getElementById('detail_quantity');
        let qty = parseInt(input.value) || 1;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        input.value = qty;

        const total = (unitPrice * qty).toFixed(2);
        document.getElementById('detail_total_display').innerText = '$' + total;
    }
</script>
@endsection
