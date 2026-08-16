<table class="table table-bordered" id="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->nama_kategori }}</td>
                <td>
                    <a href="#" class="btn btn-primary" id="edit" data-id="{{ $row->id_kategori }}">Ubah</a> |
                    <button type="button" id="confirm_delete" class="btn btn-danger" data-id="{{ $row->id_kategori }}">Hapus</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="{{ asset('admin/docs/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/docs/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#table').DataTable();</script>
