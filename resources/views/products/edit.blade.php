<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>

<h1>Edit Product</h1>

<form action="{{ route('products.update', $product) }}" method="POST">

    @csrf
    @method('PUT')

    <div>
        <label>Product Name:</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $product->name) }}"
        >

        @error('name')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label>Description:</label>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>
    </div>

    <br>
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

<br>
    <div>
        <label>Price:</label>
        <input
            type="number"
            name="price"
            step="0.01"
            value="{{ old('price', $product->price) }}"
        >

        @error('price')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label>Quantity:</label>
        <input
            type="number"
            name="quantity"
            value="{{ old('quantity', $product->quantity) }}"
        >

        @error('quantity')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button type="submit">Update Product</button>

</form>

<br>

<a href="{{ route('products.index') }}">Back to Products</a>

</body>
</html>