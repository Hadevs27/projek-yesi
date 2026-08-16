<input type="hidden" name="id" value="{{ $produk->id_barang }}">
<input type="hidden" name="aksi" value="update">
<div class="form-group">
    <label>Nama Produk</label>
    <input class="form-control" name="nama" required="required" placeholder="Nama produk" value="{{ $produk->nama_barang }}">
</div>
<div class="form-group">
    <label>Kategori</label>
    <select class="form-control" name="kategori" required>
        @foreach ($kategori as $k)
            <option value="{{ $k->id_kategori }}" {{ $produk->id_kategori == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Harga (Rp)</label>
    <input type="number" class="form-control" name="harga" required="required" value="{{ $produk->harga_barang }}">
</div>
<div class="form-group">
    <label>Stok</label>
    <input type="number" class="form-control" name="stok" required="required" value="{{ $produk->stok_barang }}">
</div>
<div class="form-group">
    <label>Deskripsi</label>
    <textarea class="form-control" name="deskripsi" required>{{ $produk->deskripsi_barang }}</textarea>
</div>
<div class="form-group">
    <label>Foto <small>(Biarkan kosong jika tidak diubah)</small></label>
    <input type="file" class="form-control" name="foto" accept="image/*">
    <br>
    <img src="{{ asset('assets/produk/' . $produk->foto_barang) }}" width="100px" height="100px">
</div>
