<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i> Product Details</h4>
                </div>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: cover;">
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>ID:</strong>
                            <p class="text-muted">{{ $product->id }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Category:</strong>
                            <p><span class="badge bg-secondary">{{ $product->category ? $product->category->name : 'No Category' }}</span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Product Name:</strong>
                        <h5 class="text-dark fw-bold">{{ $product->name }}</h5>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="text-secondary bg-light p-3 rounded">{{ $product->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Price:</strong>
                            <p class="fs-4 fw-bold text-success">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Quantity:</strong>
                            <p class="fs-5 fw-semibold">{{ $product->quantity }} in stock</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <p class="text-muted small">{{ $product->created_at ? $product->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i> Edit Product
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>