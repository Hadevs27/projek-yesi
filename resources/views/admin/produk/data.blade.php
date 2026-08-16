<table class="table table-bordered" id="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga (Rp)</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $row->stok_barang }}</td>
                <td>{{ number_format($row->harga_barang, 0, ',', '.') }}</td>
                <td>
                    <a href="#" class="btn btn-primary" id="edit" data-id="{{ $row->id_barang }}">Ubah</a> |
                    <button type="button" id="confirm_delete" class="btn btn-danger" data-id="{{ $row->id_barang }}">Hapus</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="{{ asset('admin/docs/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/docs/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#table').DataTable();</script>
