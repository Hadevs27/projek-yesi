<div class="row about-us">
@foreach($products as $product)
    <div class="col-md-3">
        <br>
        <div class="card">
            <a href="{{ url('product/' . $product->id_barang) }}"
               title="{{ $product->nama_barang }}">
                <img src="{{ asset('assets/produk/' . $product->foto_barang) }}" class="card-img-top"
                     alt="{{ $product->nama_barang }}" height="auto">
                <div class="card-body">
                    <h5 class="card-title text-center">
                        {{ $product->nama_barang }}
                    </h5>
                </div>
            </a>
            <a href="{{ url('product/' . $product->id_barang) }}" class="btn btn-primary btn-block">
                Pesan
            </a>
        </div>
    </div>
@endforeach
</div>
