<!DOCTYPE html>
<html>
<head>
    <title>Category Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="mb-0">Category Details</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>ID:</strong>
                        <p>{{ $category->id }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Category Name:</strong>
                        <p>{{ $category->name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Created At:</strong>
                        <p>{{ $category->created_at ? $category->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Total Products:</strong>
                        <p>{{ $category->products()->count() }}</p>
                    </div>

                    <hr>

                    <a
                        href="{{ route('categories.edit', $category) }}"
                        class="btn btn-warning"
                    >
                        Edit Category
                    </a>

                    <a
                        href="{{ route('categories.index') }}"
                        class="btn btn-secondary"
                    >
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
