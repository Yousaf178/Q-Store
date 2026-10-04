<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
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
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i> Add Product</h4>
                </div>

                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ route('products.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter product name"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Brand (Optional)</label>
                            <input
                                type="text"
                                name="brand"
                                class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand') }}"
                                placeholder="Enter product brand"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3"
                                placeholder="Enter product description"
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
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
                        </div>

                        <!-- Product Picture -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Picture</label>
                            <input 
                                type="file" 
                                name="image" 
                                id="productImageInput" 
                                class="form-control @error('image') is-invalid @enderror" 
                                accept="image/*"
                                onchange="previewImage(this)"
                            >
                            <div class="form-text">Supported formats: JPG, PNG, WEBP, GIF (Max: 2MB)</div>
                            <div class="mt-2 text-center d-none" id="imagePreviewContainer">
                                <img id="imagePreview" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Price ($)</label>
                                <input
                                    type="number"
                                    name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    step="0.01"
                                    value="{{ old('price') }}"
                                    placeholder="0.00"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Quantity / Stock</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity', 0) }}"
                                    placeholder="0"
                                    required
                                >
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success fw-semibold flex-grow-1">
                                <i class="bi bi-check-circle me-1"></i> Save Product
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const container = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        container.classList.add('d-none');
    }
}
</script>

</body>
</html>