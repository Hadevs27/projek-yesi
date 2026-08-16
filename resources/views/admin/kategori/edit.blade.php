<input type="hidden" name="id" value="{{ $kategori->id_kategori }}">
<input type="hidden" name="aksi" value="update">
<div class="form-group">
    <label>Kategori</label>
    <input class="form-control" name="kategori" required="required" placeholder="Kategori" value="{{ $kategori->nama_kategori }}">
</div>
