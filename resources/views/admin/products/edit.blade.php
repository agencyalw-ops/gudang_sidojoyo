<h1>Edit Product</h1>

<form method="POST" action="/admin/products/{{ $product->id }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $product->name }}" placeholder="Name">
    <input type="text" name="sku" value="{{ $product->sku }}" placeholder="SKU">
    <input type="number" name="price" value="{{ $product->price }}">
    <input type="number" name="stock" value="{{ $product->stock }}">

    <input type="text" name="unit" value="{{ $product->unit }}">
    <textarea name="description">{{ $product->description }}</textarea>

    <label>
        <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
        Active
    </label>

    <button type="submit">Update</button>
</form>