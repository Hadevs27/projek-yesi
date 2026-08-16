<input type="hidden" name="id" value="{{ $pesanan->id_pesanan }}">
<div class="row">
    <div class="col-md-6">
        <p><b>Data Pemesan</b></p>
        <p>Nama: {{ $pesanan->nama_pesanan }}</p>
        <p>No HP: {{ $pesanan->no_hp_pesanan }}</p>
        <p>Email: {{ $pesanan->email_pesanan }}</p>
        <p>Alamat: {{ $pesanan->alamat_pesanan }}</p>
    </div>
    <div class="col-md-6">
        <p><b>Status Pesanan</b></p>
        <select class="form-control" name="status_pesanan">
            <option value="Menunggu Pembayaran" {{ $pesanan->status_pesanan == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran / Menunggu Konfirmasi</option>
            <option value="Diproses" {{ $pesanan->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="Dikirim" {{ $pesanan->status_pesanan == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Ditolak" {{ $pesanan->status_pesanan == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="Dibatalkan" {{ $pesanan->status_pesanan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>
</div>
<hr>
@if($pesanan->bukti_pembayaran)
<div class="row">
    <div class="col-md-12">
        <p><b>Bukti Pembayaran (QRIS)</b></p>
        @if(str_starts_with($pesanan->bukti_pembayaran, 'data:image') || str_starts_with($pesanan->bukti_pembayaran, 'http'))
            <img src="{{ $pesanan->bukti_pembayaran }}" alt="Bukti Pembayaran" style="max-width: 300px; border-radius: 10px; border: 1px solid #ddd;">
        @else
            <img src="{{ asset('assets/bukti_pembayaran/' . $pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="max-width: 300px; border-radius: 10px; border: 1px solid #ddd;">
        @endif
    </div>
</div>
<hr>
@endif
<p><b>Daftar Produk</b></p>
<table class="table table-bordered">
    <tr>
        <th>Gambar</th>
        <th>Produk</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>
    @foreach ($pesanan->detailPesanan as $detail)
    <tr>
        <td>
            <img src="{{ asset('assets/produk/' . $detail->barang->foto_barang) }}" alt="{{ $detail->barang->nama_barang }}" width="50" style="border-radius: 5px;">
        </td>
        <td>{{ $detail->barang->nama_barang }}</td>
        <td>Rp. {{ number_format($detail->barang->harga_barang, 0, ',', '.') }}</td>
        <td>{{ $detail->jumlah_pesanan }}</td>
        <td>Rp. {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3" class="text-right">Total Ongkir & Total Harga:</td>
        <td><b>Rp. {{ number_format($pesanan->total_harga_pesanan, 0, ',', '.') }}</b></td>
    </tr>
</table>
