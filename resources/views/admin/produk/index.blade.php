@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Produk</button><br> <br>
                <div id="data-produk"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-tambah" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="form-tambah" method="post" action="{{ url('admin/produk/action') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Menambahkan Produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input class="form-control" name="nama" required="required" placeholder="Nama produk">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="kategori" required>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" required="required" placeholder="Misal : 10000">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control" name="stok" required="required" placeholder="Misal : 50">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto <small>(Biarkan kosong jika tidak ada)</small></label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <input type="hidden" name="aksi" value="insert">
            </form>
        </div>
    </div>
</div>

<div id="modal-edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="form-edit" method="post" action="{{ url('admin/produk/action') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Edit Produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="data-edit"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-hapus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" action="{{ url('admin/produk/delete') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Hapus produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="delete-produk"></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadData();
    
    function loadData() {
        $.get('{{ url("admin/produk/data") }}', function(data) {
            $('#data-produk').html(data);
        });
    }

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        $("#modal-edit").modal('show');
        $.post('{{ url("admin/produk/edit") }}', { 
            _token: '{{ csrf_token() }}',
            id: $(this).attr('data-id') 
        }, function(html) {
            $("#data-edit").html(html);
        });
    });

    $(document).on('click', '#confirm_delete', function(e) {
        e.preventDefault();
        $("#modal-hapus").modal('show');
        $.post('{{ url("admin/produk/delete-confirm") }}', { 
            _token: '{{ csrf_token() }}',
            id: $(this).attr('data-id') 
        }, function(html) {
            $("#delete-produk").html(html);
        });
    });
});
</script>
@endpush
