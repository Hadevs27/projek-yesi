<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <title>Pesan Online Ai-CHA!</title>
</head>
<body>
    <br><br><br><br>

    <div class="container">

        <h1 class="display-5">Detail Pesanan</h1>
        <hr>

        <p>Berikut adalah detail pesanan Anda.</p>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Status Pesanan</b></label>
            <div class="col-sm-10">
                <b>
                    {{ $pesanan->jenis_pembayaran == "COD" && $pesanan->status_pesanan == "Menunggu Pembayaran" ? "Menunggu Konfirmasi" : $pesanan->status_pesanan }}
                </b>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Tanggal Pemesanan</b></label>
            <div class="col-sm-10">
                <b>
                    {{ $pesanan->tanggal_pesanan }}
                </b>
            </div>
        </div>
        <hr>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Kode Pesanan</b></label>
            <div class="col-sm-10">
                <b>
                    {{ $pesanan->id_pesanan }}
                </b>
            </div>
        </div>
        <hr>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Daftar Produk</b></label>
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
            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Total Harga</b></label>
            <div class="col-sm-10">
                Rp. {{ number_format($pesanan->detailPesanan->sum('subtotal_harga'), 0, ',', '.') }}
            </div>
        </div>
        <hr>

        <div class="form-group">
            <label><b>Informasi Pembayaran</b></label>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Total Pembayaran</label>
                <div class="col-sm-10">
                    Rp. {{ number_format($pesanan->total_harga_pesanan, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <hr>
        <br><br>

        <div class="form-group">
            <label><b>Pembayaran</b></label>
            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label mb-0"><b>Status Pembayaran</b></label>
                <div class="col-sm-10">
                    <b>
                        @if ($pesanan->jenis_pembayaran == 'COD')
                            COD (Cash On Delivery)
                        @elseif ($pesanan->status_pesanan == 'Menunggu Pembayaran')
                            Belum Dibayar
                        @elseif ($pesanan->status_pesanan == 'Diproses' || $pesanan->status_pesanan == 'Dikirim' || $pesanan->status_pesanan == 'Selesai')
                            Lunas (Terverifikasi Otomatis)
                        @else
                            {{ $pesanan->status_pesanan }}
                        @endif
                    </b>
                </div>
            </div>
        </div>

        <br><br>
        <p>© Pesan Online Ai-CHA!</p>
        <br><br>

    </div>

</body>
</html>
<script>
    window.print();
</script>
