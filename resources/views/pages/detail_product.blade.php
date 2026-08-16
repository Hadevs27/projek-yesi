@extends('layouts.app')

@section('content')
<div class="jumbotron">
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('assets/produk/' . $product->foto_barang) }}" height="500px" width="100%">
                </div>
                <div class="col-md-6">
                    <h3 class="font-weight-bold">
                        {{ $product->nama_barang }}
                    </h3>
                    <p class="text-justify display-5">
                        {{ $product->deskripsi_barang }}
                    </p>
                    <br>
                    <h5>Rp. {{ number_format($product->harga_barang, 0, ',', '.') }}</h5><br>
                    
                    @if ($product->stok_barang > 1)
                        <h4 class="text-success">Stok Tersedia: {{ $product->stok_barang }} barang</h4>
                        <br>
                    @endif
                    
                    <form role="form" id="form-tambah" action="{{ url('cart/add') }}" method="post">
                        @csrf
                        <input type="hidden" name="id_barang" value="{{ $product->id_barang }}">
                        <div class="form-group">
                            <label>Jumlah pesan</label>
                            <input type="number" class="form-control" id="qty" name="jumlah_pesan" required value="1" min="1"
                                max="{{ $product->stok_barang }}">
                        </div>
                        @if ($product->stok_barang < 1)
                            <h4 class="text-danger">Stok Habis</h4>
                        @else
                            <button type="submit" name="addcart" class="btn btn-primary">Tambah ke keranjang</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
