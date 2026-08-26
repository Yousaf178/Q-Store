@extends('layouts.app')

@section('title', 'Products - QShop')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="p-4 bg-white rounded-3 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted mb-0">Browse items, add products to your cart, and checkout with secure online payment.</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('cart.index') }}" class="btn btn-warning fw-semibold">
                        <i class="bi bi-cart3 me-1"></i> View Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Products -->
<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('dashboard') }}" method="GET">
            <div class="input-group input-group-lg shadow-sm">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search products by name or description..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Products Grid -->
<div class="row g-4">
    @if(isset($products) && $products->count())
        @foreach($products as $product)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 product-card overflow-hidden">
                    <!-- Product Picture -->
                    <a href="{{ route('products.details', $product) }}" class="d-block bg-light text-center border-bottom">
                        <img 
                            src="{{ $product->image_url }}" 
                            alt="{{ $product->name }}" 
                            class="w-100" 
                            style="height: 200px; object-fit: cover;"
                        >
                    </a>

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-secondary-subtle text-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            <span class="fw-bold text-success fs-5">${{ number_format($product->price, 2) }}</span>
                        </div>

                        <h5 class="card-title fw-bold">
                            <a href="{{ route('products.details', $product) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($product->description ?? 'No description available.', 85) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-3">
                            <span class="small text-muted">
                                <i class="bi bi-box me-1"></i> Stock: <strong>{{ $product->quantity }}</strong>
                            </span>

                            @if($product->quantity > 0)
                                <span class="badge bg-success-subtle text-success px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> In Stock
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Out of Stock
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-2">
                            @if($product->quantity > 0)
                                <div class="col-6">
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                                            <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="buy_now" value="1">
                                        <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold">
                                            <i class="bi bi-credit-card me-1"></i> Buy Now
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                                        Out of Stock
                                    </button>
                                </div>
                            @endif

                            <div class="col-12">
                                <a href="{{ route('products.details', $product) }}" class="btn btn-light btn-sm w-100 text-muted border">
                                    <i class="bi bi-info-circle me-1"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12 mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-info text-center py-4">
                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                <h5>No products found.</h5>
                <p class="text-muted mb-0">Check back later or try searching with different keywords.</p>
            </div>
        </div>
    @endif
</div>
@endsection
