@extends('layouts.app')

@section('content')
<div class="container-custom">

    <div id="demo" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
        </ul>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/banner3.png') }}" alt="Banner Ai-CHA 1" width="100%" height="500">
                <div class="carousel-caption">
                    <h1><b>Ai-CHA is centered in indonesia and covers nine countries in Southeast Asia.</b></h1>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/banner2.jpg') }}" alt="Banner Ai-CHA 2" width="100%" height="500">
                <div class="carousel-caption">
                    <h1><b>We take great care to ensure all customers enjoy a good quality of tea.</b></h1>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>

    <br><br><br>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-4">
                <div class="garis"></div>
            </div>
            <div class="col-md-4">
                <h1 class="display-5 text-center">Produk Kami</h1>
            </div>
            <div class="col-md-4">
                <div class="garis"></div>
            </div>
        </div>

        <br>
        <br>

        <div class="form-group col-md-3">
            <label for="kategori">Pilih Kategori</label>

            <select class="form-control" id="kategori">
                <option value="show-all" selected="selected">--- Pilihan Kategori ---</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div id="data-product"></div>
        <br><br>

    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        function getAll() {
            $.ajax({
                url: '{{ url("load-products") }}',
                data: 'action=show-all',
                cache: false,
                success: function(response) {
                    $("#data-product").html(response);
                }
            });
        }

        getAll();

        $("#kategori").change(function() {
            var pil = $(this).find(":selected").val();
            var dataString = 'action=' + pil;

            $.ajax({
                url: '{{ url("load-products") }}',
                data: dataString,
                cache: false,
                success: function(response) {
                    $("#data-product").html(response);
                }
            });
        })
    })
</script>
@endpush
