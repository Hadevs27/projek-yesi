<table class="table table-bordered" id="table">
    <thead>
        <tr>
            <th>Kode Pesanan</th>
            <th>Nama Pemesan</th>
            <th>Tanggal</th>
            <th>Total Pembayaran</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->id_pesanan }}</td>
                <td>{{ $row->nama_pesanan }}</td>
                <td>{{ $row->tanggal_pesanan }}</td>
                <td>Rp. {{ number_format($row->total_harga_pesanan, 0, ',', '.') }}</td>
                <td>{{ $row->jenis_pembayaran }}</td>
                <td>
                    @if ($row->status_pesanan == 'Menunggu Pembayaran' && $row->jenis_pembayaran == 'COD')
                        <span class="badge badge-warning">Menunggu Konfirmasi</span>
                    @elseif ($row->status_pesanan == 'Menunggu Pembayaran')
                        <span class="badge badge-warning">{{ $row->status_pesanan }}</span>
                    @elseif ($row->status_pesanan == 'Diproses' || $row->status_pesanan == 'Selesai')
                        <span class="badge badge-success">{{ $row->status_pesanan }}</span>
                    @else
                        <span class="badge badge-secondary">{{ $row->status_pesanan }}</span>
                    @endif
                </td>
                <td>
                    <a href="#" class="btn btn-primary" id="detail" data-id="{{ $row->id_pesanan }}">Detail</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="{{ asset('admin/docs/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/docs/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#table').DataTable();</script>
