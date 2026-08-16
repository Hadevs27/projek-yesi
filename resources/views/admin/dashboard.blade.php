@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-small info coloured-icon"><i class="icon fa fa-list-alt fa-3x"></i>
            <div class="info">
                <h4>Kategori</h4>
                <p><b>{{ $kategori }}</b></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-small primary coloured-icon"><i class="icon fa fa-desktop fa-3x"></i>
            <div class="info">
                <h4>Produk</h4>
                <p><b>{{ $barang }}</b></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
            <div class="info">
                <h4>Pesanan</h4>
                <p><b>{{ $pesanan }}</b></p>
            </div>
        </div>
    </div>
</div>
<div class="carousel-inner">
    <div class="carousel-item active">
        <div style="width: 100%;max-width: 100%;max-height: 300px;overflow: hidden; display: flex; justify-content: center; align-items: center;">
            <img src="{{ asset('assets/img/banner1.png') }}" width="100%" height="auto" alt="Ai-CHA">
        </div>
    </div>
</div>
@endsection
