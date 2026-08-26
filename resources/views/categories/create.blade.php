<!DOCTYPE html>
<html>
<head>

    <title>Add Category</title>

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
                    <h3 class="mb-0">Add Category</h3>
                </div>

                <div class="card-body">

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>Please fix the following errors:</strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <form
                        action="{{ route('categories.store') }}"
                        method="POST"
                    >

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Category Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Enter category name"
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Save Category
                        </button>

                        <a
                            href="{{ route('categories.index') }}"
                            class="btn btn-secondary"
                        >
                            Back
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>