<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-header">
                    <h3 class="mb-0">Product Details</h3>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <strong>ID:</strong>
                        <p>{{ $product->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Product Name:</strong>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $product->description ?? 'No description' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Price:</strong>
                        <p>${{ number_format($product->price, 2) }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Quantity:</strong>
                        <p>{{ $product->quantity }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <p>{{ $product->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <hr>

                    <a
                        href="{{ route('products.edit', $product) }}"
                        class="btn btn-warning"
                    >
                        Edit Product
                    </a>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-secondary"
                    >
                        Back to Products
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>