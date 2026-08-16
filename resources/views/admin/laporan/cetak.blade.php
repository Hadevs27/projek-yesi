<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pesanan - Ai-CHA</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/docs/css/main.css') }}">
    <style>
        body { background-color: white; padding: 20px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="text-center">
        <h2>Laporan Pendapatan Ai-CHA</h2>
        <p>
            Bulan: {{ $bulan == 'all' ? 'Semua Bulan' : $bulan }} | Tahun: {{ $tahun == 'all' ? 'Semua Tahun' : $tahun }}
        </p>
    </div>
    <hr>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pemesan</th>
                <th>Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1;
                $total_pendapatan = 0; 
            @endphp
            @foreach($data as $row)
                @php $total_pendapatan += $row->total_harga_pesanan; @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $row->id_pesanan }}</td>
                    <td>{{ $row->tanggal_pesanan }}</td>
                    <td>{{ $row->nama_pesanan }}</td>
                    <td>Rp. {{ number_format($row->total_harga_pesanan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><b>Total Pendapatan</b></td>
                <td><b>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
