<h1>Product List</h1>

<a href="/admin/products/create">+ Tambah Product</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>SKU</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Action</th>
    </tr>

    @foreach($products as $p)
    <tr>
        <td>{{ $p->name }}</td>
        <td>{{ $p->sku }}</td>
        <td>{{ $p->price }}</td>
        <td>{{ $p->stock }}</td>
        <td>
    <a href="/admin/products/{{ $p->id }}/edit">Edit</a>

    <form action="/admin/products/{{ $p->id }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Hapus product ini?')">
            Delete
        </button>
    </form>
</td>
    </tr>
    @endforeach
</table>