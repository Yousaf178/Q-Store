@extends('layouts.app')

@section('title', 'Online Checkout & Payment - QShop')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold mb-1"><i class="bi bi-shield-lock text-success me-2"></i> Secure Online Checkout</h2>
        <p class="text-muted mb-0">Complete your billing information and choose your preferred online payment method.</p>
    </div>
</div>

<form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
    @csrf

    <div class="row g-4">
        <!-- Billing & Payment Details -->
        <div class="col-lg-8">
            <!-- 1. Customer & Shipping Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt text-primary me-2"></i> 1. Shipping & Customer Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label fw-semibold">Full Name</label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                id="customer_name" 
                                class="form-control @error('customer_name') is-invalid @enderror" 
                                value="{{ old('customer_name', Auth::user()->name) }}" 
                                placeholder="Enter full name"
                                required
                            >
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_email" class="form-label fw-semibold">Email Address</label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                id="customer_email" 
                                class="form-control @error('customer_email') is-invalid @enderror" 
                                value="{{ old('customer_email', Auth::user()->email) }}" 
                                placeholder="Enter email address"
                                required
                            >
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="shipping_address" class="form-label fw-semibold">Shipping / Delivery Address</label>
                            <textarea 
                                name="shipping_address" 
                                id="shipping_address" 
                                class="form-control @error('shipping_address') is-invalid @enderror" 
                                rows="3" 
                                placeholder="Street address, City, State, Postal Code"
                                required
                            >{{ old('shipping_address', '123 Market Street, Suite 400, New York, NY 10001') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Online Payment Method -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card text-success me-2"></i> 2. Online Payment Gateway</h5>
                    <span class="badge bg-success-subtle text-success">
                        <i class="bi bi-lock-fill me-1"></i> SSL 256-bit Encrypted
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- Payment Method Selector -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Choose Payment Method</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="credit_card" {{ old('payment_method', 'credit_card') === 'credit_card' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <label class="btn btn-outline-primary w-100 p-3 text-start" for="pay_card">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <i class="bi bi-credit-card-2-front fs-4 text-primary"></i>
                                        <span class="badge bg-primary">Instant</span>
                                    </div>
                                    <div class="fw-bold">Credit / Debit Card</div>
                                    <div class="small text-muted">Visa, Mastercard, Amex</div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_paypal" value="paypal" {{ old('payment_method') === 'paypal' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <label class="btn btn-outline-info w-100 p-3 text-start" for="pay_paypal">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <i class="bi bi-paypal fs-4 text-info"></i>
                                        <span class="badge bg-info text-dark">Online</span>
                                    </div>
                                    <div class="fw-bold">PayPal Account</div>
                                    <div class="small text-muted">Fast & secure checkout</div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_bank" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <label class="btn btn-outline-secondary w-100 p-3 text-start" for="pay_bank">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <i class="bi bi-bank fs-4 text-secondary"></i>
                                        <span class="badge bg-secondary">Direct</span>
                                    </div>
                                    <div class="fw-bold">Bank Transfer</div>
                                    <div class="small text-muted">Direct online transfer</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card Details Fields -->
                    <div id="cardPaymentFields" class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-credit-card me-1"></i> Card Information</h6>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillTestCard()">
                                <i class="bi bi-magic me-1"></i> Auto-fill Demo Card
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="card_name" class="form-label small fw-semibold">Name on Card</label>
                                <input 
                                    type="text" 
                                    name="card_name" 
                                    id="card_name" 
                                    class="form-control @error('card_name') is-invalid @enderror" 
                                    value="{{ old('card_name', Auth::user()->name) }}" 
                                    placeholder="e.g. John Doe"
                                >
                                @error('card_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="card_number" class="form-label small fw-semibold">Card Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                    <input 
                                        type="text" 
                                        name="card_number" 
                                        id="card_number" 
                                        class="form-control @error('card_number') is-invalid @enderror" 
                                        value="{{ old('card_number', '4242 4242 4242 4242') }}" 
                                        placeholder="4242 •••• •••• 4242"
                                    >
                                </div>
                                @error('card_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="card_expiry" class="form-label small fw-semibold">Expiration Date</label>
                                <input 
                                    type="text" 
                                    name="card_expiry" 
                                    id="card_expiry" 
                                    class="form-control @error('card_expiry') is-invalid @enderror" 
                                    value="{{ old('card_expiry', '12/28') }}" 
                                    placeholder="MM/YY"
                                >
                                @error('card_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="card_cvv" class="form-label small fw-semibold">Security Code (CVV)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input 
                                        type="password" 
                                        name="card_cvv" 
                                        id="card_cvv" 
                                        class="form-control @error('card_cvv') is-invalid @enderror" 
                                        value="{{ old('card_cvv', '123') }}" 
                                        placeholder="123" 
                                        maxlength="4"
                                    >
                                </div>
                                @error('card_cvv')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- PayPal Notice -->
                    <div id="paypalNotice" class="p-3 bg-info-subtle text-info-emphasis rounded-3 mb-3 border border-info-subtle d-none">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-paypal fs-2 me-3 text-info"></i>
                            <div>
                                <h6 class="fw-bold mb-1">PayPal Instant Checkout</h6>
                                <p class="mb-0 small">You will be securely authenticated with your PayPal account upon clicking place order.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Notice -->
                    <div id="bankNotice" class="p-3 bg-secondary-subtle text-secondary-emphasis rounded-3 mb-3 border d-none">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bank fs-2 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Direct Bank Wire / Online Transfer</h6>
                                <p class="mb-0 small">Order will be completed instantly with electronic direct payment verification.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary on Checkout -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-bag-check text-primary me-2"></i> Order Items</h5>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cart as $item)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <img 
                                        src="{{ $item['image_url'] ?? "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40'><rect fill='%23f1f3f5' width='40' height='40'/></svg>" }}" 
                                        alt="{{ $item['name'] }}" 
                                        class="rounded border" 
                                        style="width: 40px; height: 40px; object-fit: cover;"
                                    >
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Online Processing Fee:</span>
                        <span class="text-success fw-semibold">FREE</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 fw-bold">Total to Pay:</span>
                        <span class="fs-3 fw-bold text-success">${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold py-3">
                            <i class="bi bi-check-circle-fill me-2"></i> Pay & Complete Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Edit Cart
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 py-3 text-center small text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i> 100% Secure Transaction
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function togglePaymentFields() {
    const isCard = document.getElementById('pay_card').checked;
    const isPaypal = document.getElementById('pay_paypal').checked;
    const isBank = document.getElementById('pay_bank').checked;

    document.getElementById('cardPaymentFields').classList.toggle('d-none', !isCard);
    document.getElementById('paypalNotice').classList.toggle('d-none', !isPaypal);
    document.getElementById('bankNotice').classList.toggle('d-none', !isBank);
}

function fillTestCard() {
    document.getElementById('card_name').value = '{{ Auth::user()->name }}';
    document.getElementById('card_number').value = '4532 8921 4829 9012';
    document.getElementById('card_expiry').value = '08/29';
    document.getElementById('card_cvv').value = '888';
}

document.addEventListener('DOMContentLoaded', togglePaymentFields);
</script>
@endsection
