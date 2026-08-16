<input type="hidden" name="id_barang" value="{{ $id }}">
<div class="form-group">
    <label>Nama Barang</label>
    <input type="text" class="form-control" name="nama_barang" value="{{ $nama }}" readonly>
</div>
<div class="form-group">
    <label>Jumlah pesan</label>
    <input type="number" class="form-control" name="jumlah_pesan" required value="{{ $qty }}" min="1" max="{{ $max }}">
</div>
