<!DOCTYPE html>
<html>
<head>

    <title>Add Product</title>

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

            <div class="card">

                <div class="card-header">
                    <h3 class="mb-0">Add Product</h3>
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
                        action="{{ route('products.store') }}"
                        method="POST"
                    >

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Product Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Enter product name"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                placeholder="Enter product description"
                            >{{ old('description') }}</textarea>

                        </div>

<div class="mb-3">

    <label class="form-label">
        Category
    </label>

    <select name="category_id" class="form-select">

        <option value="">Select Category</option>

        @foreach($categories as $category)

            <option
                value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>

        @endforeach

    </select>

    @error('category_id')
        <p style="color:red;">{{ $message }}</p>
    @enderror

</div>
                        

                        <div class="mb-3">

                            <label class="form-label">
                                Price
                            </label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                step="0.01"
                                value="{{ old('price') }}"
                                placeholder="Enter price"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Quantity
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                value="{{ old('quantity') }}"
                                placeholder="Enter quantity"
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Save Product
                        </button>

                        <a
                            href="{{ route('products.index') }}"
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