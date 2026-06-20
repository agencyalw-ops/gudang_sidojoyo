<h1>Tambah Product</h1>

<form method="POST" action="/admin/products">
    @csrf

    <input type="text" name="name" placeholder="Nama Product"><br><br>

    <input type="text" name="sku" placeholder="SKU"><br><br>

    <input type="number" name="price" placeholder="Harga"><br><br>

    <input type="number" name="stock" placeholder="Stock"><br><br>

    <input type="text" name="unit" placeholder="Unit (pcs, box)"><br><br>

    <textarea name="description" placeholder="Deskripsi"></textarea><br><br>

    <button type="submit">Simpan</button>
</form>