<!DOCTYPE html>
<html>
<head>

    <title>Categories</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Categories</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-1">
                &larr; Admin Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark me-1">
                Manage Products
            </a>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                + Add Category
            </a>
        </div>
    </div>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if($categories->count())

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @foreach($categories as $category)

                    <tr>

                        <td>{{ $category->id }}</td>

                        <td>{{ $category->name }}</td>

                        <td>

                            <a
                                href="{{ route('categories.show', $category) }}"
                                class="btn btn-info btn-sm"
                            >
                                View
                            </a>

                            <a
                                href="{{ route('categories.edit', $category) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('categories.destroy', $category) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this category?');"
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

    @else

        <div class="alert alert-info">
            No categories found.
        </div>

    @endif

</div>

</body>
</html>