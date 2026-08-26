<!DOCTYPE html>
<html>
<head>
    <title>Products</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1>Products</h1>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            + Add Product
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
<!-- yousaf put code for search -->
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
            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary"
            >
                Clear
            </a>
        @endif

    </div>

</form>
<!-- end -->
    @if($products->count())

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th width="220">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($products as $product)

                        <tr>

                            <td>{{ $product->id }}</td>

                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>

                            <td>
                                {{ $product->description }}
                            </td>
<td>
    {{ $product->category->name ?? 'No Category' }}
</td>
                            <td>
                                ${{ number_format($product->price, 2) }}
                            </td>

                            <td>
                                {{ $product->quantity }}
                            </td>

                            <td>

                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn btn-warning btn-sm">
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

    <button
        type="submit"
        class="btn btn-danger btn-sm"
    >
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