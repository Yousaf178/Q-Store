<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>QShop Products</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-1">
                &larr; Admin Dashboard
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-dark me-1">
                Manage Categories
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                + Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search products..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Clear
                </a>
            @endif
        </div>
    </form>

    @if($products->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="60">ID</th>
                        <th width="80">Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="text-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>
                                {{ Str::limit($product->description, 60) }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $product->category->name ?? 'No Category' }}</span>
                            </td>
                            <td class="fw-bold text-success">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">
                                    View
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form
                                    action="{{ route('products.destroy', $product) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No products found.
        </div>
    @endif

</div>

</body>
</html>