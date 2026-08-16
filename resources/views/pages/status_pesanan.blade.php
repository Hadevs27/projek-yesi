@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; display: flex; flex-direction: column;">

    <br><br><br><br>

    <div class="container" style="flex: 1;">

        <a href="{{ url('status-pesanan') }}">&lt; Kembali</a>
        <h1 class="display-5">Status Pesanan</h1>

        @if (!$pesanan)
            <br><br>
            <label><b>Pesanan dengan kode pesanan: {{ $id_pesanan }} tidak ditemukan!</b> <a href="{{ url('status-pesanan') }}">Kembali</a></label>
        @else

            Silahkan cetak halaman <a href="" onclick="window.open('{{ url('cetak-struk/' . $id_pesanan) }}', 'newwindow','width=800,height=500'); return false;">Klik Disini</a> untuk mengingat detail pemesanan Anda.

            <hr>

            <p>Berikut adalah detail status pesanan Anda. Untuk pembatalan pesanan silakan hubungi nomor pada link berikut:
                <a href="https://wa.me/6281292695670">hubungi</a>
            </p>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Status Pesanan</b></label>
                <div class="col-sm-10">
                    <b>
                        {{ $pesanan->jenis_pembayaran == "COD" && $pesanan->status_pesanan == "Menunggu Pembayaran" ? "Menunggu Konfirmasi" : $pesanan->status_pesanan }}
                    </b>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Tanggal Pemesanan</b></label>
                <div class="col-sm-10">
                    <b>{{ $pesanan->tanggal_pesanan }}</b>
                </div>
            </div>
            <hr>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Kode Pesanan</b></label>
                <div class="col-sm-10">
                    <b>{{ $pesanan->id_pesanan }}</b>
                </div>
            </div>
            <hr>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Daftar Produk</b></label>
                <div class="col-sm-10">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Jumlah pesanan</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                        @foreach ($pesanan->detailPesanan as $keranjang)
                            <tr>
                                <td class="text-center">{{ $keranjang->barang->nama_barang }}</td>
                                <td class="text-center">Rp. {{ number_format($keranjang->barang->harga_barang, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $keranjang->jumlah_pesanan }}</td>
                                <td class="text-center">Rp. {{ number_format($keranjang->subtotal_harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Total Harga</b></label>
                <div class="col-sm-10">
                    Rp. {{ number_format($pesanan->detailPesanan->sum('subtotal_harga'), 0, ',', '.') }}
                </div>
            </div>
            <hr>

            <div class="form-group">
                <label><b>Informasi Pembayaran</b></label>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Total Pembayaran</b></label>
                    <div class="col-sm-10">
                        Rp. {{ number_format($pesanan->total_harga_pesanan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <hr>

            <!-- METODE PEMBAYARAN FULL AUTOMATIC & COD -->
            @if ($pesanan->jenis_pembayaran == 'COD')
                <h5 class="text-info">Pembayaran dilakukan secara COD (Cash On Delivery)</h5>

            @else

                @if ($pesanan->status_pesanan == 'Menunggu Pembayaran' && !empty($pesanan->snap_token))
                    <div class="card p-4 my-3 border-primary shadow-sm">
                        <h4 class="text-primary font-weight-bold">Selesaikan Pembayaran</h4>
                        <p>Klik tombol di bawah untuk membayar menggunakan Virtual Account (BCA, Mandiri, BNI, dll), QRIS, atau E-Wallet via Payment Gateway.</p>
                        <div class="d-flex align-items-center gap-2">
                            <button id="pay-button" class="btn btn-success btn-lg mr-2">Bayar Sekarang Otomatis</button>
                            <button id="cancel-button" class="btn btn-outline-danger btn-lg">Batalkan Pesanan</button>
                        </div>
                    </div>
                @elseif ($pesanan->status_pesanan == 'Diproses')
                    <div class="alert alert-success p-3" role="alert">
                        <h4 class="alert-heading">Pembayaran Lunas!</h4>
                        <p class="mb-0">Transaksi berhasil diproses otomatis oleh sistem Payment Gateway. Pesanan Anda sedang disiapkan.</p>
                    </div>
                @elseif ($pesanan->status_pesanan == 'Ditolak')
                    <div class="alert alert-danger p-3" role="alert">
                        <h4 class="alert-heading">Pesanan Dibatalkan</h4>
                        <p class="mb-0">Pesanan ini telah dibatalkan dan stok produk telah dikembalikan ke sistem.</p>
                    </div>
                @endif

            @endif

        @endif

        <br><br>
    </div> <!-- /.container -->
</div> <!-- /Wrapper Flexbox -->
@endsection

@push('scripts')
<!-- SCRIPT MIDTRANS SNAP -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-5ho-sBbjcm0g4cGS"></script>
<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            snap.pay('{{ $pesanan ? $pesanan->snap_token : '' }}', {
                onSuccess: function(result){
                    $.post('{{ url("status-pesanan/update-ajax") }}', { 
                        _token: '{{ csrf_token() }}',
                        id_pesanan: '{{ $pesanan ? $pesanan->id_pesanan : '' }}' 
                    }, function(response) {
                        alert("Pembayaran berhasil diproses!");
                        location.reload();
                    });
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                    location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran.');
                }
            });
        });
    }

    var cancelButton = document.getElementById('cancel-button');
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.")) {
                $.post('{{ url("status-pesanan/cancel") }}', { 
                    _token: '{{ csrf_token() }}',
                    id_pesanan: '{{ $pesanan ? $pesanan->id_pesanan : '' }}' 
                }, function(response) {
                    if (response.trim() === 'success') {
                        alert("Pesanan berhasil dibatalkan.");
                        location.reload();
                    } else {
                        alert("Gagal membatalkan pesanan. Silakan coba lagi.");
                    }
                });
            }
        });
    }
</script>
@endpush
