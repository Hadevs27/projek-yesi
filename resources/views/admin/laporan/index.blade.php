@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <form class="row" id="form-filter">
                    <div class="form-group col-md-3">
                        <label class="control-label">Bulan</label>
                        <select class="form-control" name="bulan" id="bulan">
                            <option value="all">Semua Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label">Tahun</label>
                        <select class="form-control" name="tahun" id="tahun">
                            <option value="all">Semua Tahun</option>
                            @php
                            $startYear = date('Y') - 5;
                            $endYear = date('Y');
                            @endphp
                            @for ($i = $endYear; $i >= $startYear; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary" type="button" id="btn-filter"><i class="fa fa-fw fa-lg fa-filter"></i>Filter</button>
                        <a href="#" class="btn btn-info" id="btn-cetak" onclick="cetakLaporan()"><i class="fa fa-fw fa-lg fa-print"></i>Cetak</a>
                    </div>
                </form>
                <hr>
                <div id="data-laporan"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadData();
    
    function loadData() {
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        $.get('{{ url("admin/laporan/data") }}', {bulan: bulan, tahun: tahun}, function(data) {
            $('#data-laporan').html(data);
        });
    }

    $('#btn-filter').click(function() {
        loadData();
    });
});

function cetakLaporan() {
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();
    var url = '{{ url("admin/laporan/cetak") }}?bulan=' + bulan + '&tahun=' + tahun;
    window.open(url, 'newwindow', 'width=800,height=600');
}
</script>
@endpush
