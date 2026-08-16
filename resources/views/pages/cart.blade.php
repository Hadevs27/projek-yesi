@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; display: flex; flex-direction: column;">
<br><br><br><br>
<div class="container" style="flex: 1;">

    @if (count($items) == 0)

        <center>
            <img src="{{ asset('assets/img/empty-cart-vector.png') }}" width="250px" height="200px">
            <br>
            <h3>Keranjang Belanja Anda Kosong</h3><br>
            <a href="{{ url('/') }}" class="btn btn-dark">Belanja Sekarang</a>
        </center>

    @else
        <div class="row">
            <div class="col-md-4">
                <div class="garis"></div>
            </div>
            <div class="col-md-4">
                <h1 class="display-5 text-center">Keranjang</h1>
            </div>
            <div class="col-md-4">
                <div class="garis"></div>
            </div>
        </div>
        <br><br>
        <div id="data-keranjang">
            <table class="table table-bordered">
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Produk</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Jumlah Pesan</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center">Opsi</th>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td class="text-center">
                            <img src="{{ asset('assets/produk/' . $item->product->foto_barang) }}" width="100px" height="100px">
                        </td>
                        <td class="text-center">
                            {{ $item->product->nama_barang }}
                        </td>
                        <td class="text-center">
                            Rp. {{ number_format($item->product->harga_barang, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            {{ $item->qty }}
                        </td>
                        <td class="text-center">
                            Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary" id="edit" data-harga="{{ $item->product->harga_barang }}"
                                data-nama="{{ $item->product->nama_barang }}"
                                data-id="{{ $item->product->id_barang }}"><span class="fa fa-edit fa-fw"></span></a> |
                            <button type="button" id="confirm_delete" class="btn btn-danger"
                                data-id="{{ $item->product->id_barang }}"><span class="fa fa-close fa-fw"></span></button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right">Total Belanja</td>
                    <td colspan="2">
                        Rp. {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
        <a href="{{ url('/') }}" class="btn btn-primary">Tambah Pesanan</a>
        <a href="{{ url('checkout') }}" class="btn btn-success">Konfirmasi dan Bayar</a>

    @endif
</div>
</div>

<div id="modal-edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="form-edit" action="{{ url('cart/add') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Edit keranjang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="data-edit">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Batal</button>
                    <button type="submit" name="addcart" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-hapus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="{{ url('cart/delete') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus keranjang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="delete-keranjang"></div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $("#modal-edit").modal('show');
            $.post('{{ url("cart/edit") }}', {
                    _token: '{{ csrf_token() }}',
                    id: $(this).attr('data-id'),
                    nama: $(this).attr('data-nama'),
                    harga: $(this).attr('data-harga')
                },
                function(html) {
                    $("#data-edit").html(html);
                }
            );
        });

        $(document).on('click', '#confirm_delete', function(e) {
            e.preventDefault();
            $("#modal-hapus").modal('show');
            $.post('{{ url("cart/delete-confirm") }}', {
                    _token: '{{ csrf_token() }}',
                    id: $(this).attr('data-id')
                },
                function(html) {
                    $("#delete-keranjang").html(html);
                }
            );
        });
    });
</script>
@endpush
