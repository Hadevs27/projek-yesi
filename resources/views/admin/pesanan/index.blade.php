@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div id="data-pesanan"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-detail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="form-edit" method="post" action="{{ url('admin/pesanan/update-status') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Detail pesanan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="data-detail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Status</button>
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
        $.get('{{ url("admin/pesanan/data") }}', function(data) {
            $('#data-pesanan').html(data);
        });
    }

    $(document).on('click', '#detail', function(e) {
        e.preventDefault();
        $("#modal-detail").modal('show');
        $.post('{{ url("admin/pesanan/detail") }}', { 
            _token: '{{ csrf_token() }}',
            id: $(this).attr('data-id') 
        }, function(html) {
            $("#data-detail").html(html);
        });
    });

    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modal-detail').modal('hide');
                loadData();
                alert('Status pesanan berhasil diperbarui!');
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan status.');
            }
        });
    });
});
</script>
@endpush
