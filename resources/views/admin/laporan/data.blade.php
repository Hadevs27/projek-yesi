<table class="table table-bordered" id="table">
    <thead>
        <tr>
            <th>Kode Pesanan</th>
            <th>Tanggal</th>
            <th>Nama Pemesan</th>
            <th>Total Belanja</th>
            <th>Metode</th>
        </tr>
    </thead>
    <tbody>
        @php $total_pendapatan = 0; @endphp
        @foreach($data as $row)
            @php $total_pendapatan += $row->total_harga_pesanan; @endphp
            <tr>
                <td>{{ $row->id_pesanan }}</td>
                <td>{{ $row->tanggal_pesanan }}</td>
                <td>{{ $row->nama_pesanan }}</td>
                <td>Rp. {{ number_format($row->total_harga_pesanan, 0, ',', '.') }}</td>
                <td>{{ $row->jenis_pembayaran }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h4>Total Pendapatan: Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</h4>

<script src="{{ asset('admin/docs/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/docs/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#table').DataTable();</script>
