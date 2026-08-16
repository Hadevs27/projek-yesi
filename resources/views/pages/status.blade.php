@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; display: flex; flex-direction: column;">
<br><br><br><br>
<br><br><br><br>

<div class="container" style="flex: 1;">
    @if ($id)
        <div class="alert alert-success" role="alert">
            Pesanan berhasil dibuat! Kode Pesanan:
            <b>
                {{ $id }}
            </b>
        </div>
    @endif
    <h1 class="display-5">Cek status pesanan Anda</h1>
    <hr>
    <form method="POST" action="{{ url('status-pesanan/cek') }}">
        @csrf
        <label>Masukkan kode pesanan yang diberikan saat melakukan pemesanan.</label>
        <div class="row">
            <div class="col">
                <div class="input-group input-group-lg">
                    <input type="text" name="kode_pesanan" class="form-control" aria-label="Large"
                        placeholder="Misal: ABC12..." aria-describedby="inputGroup-sizing-sm" required>
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary btn-lg">Cek</button>
            </div>
        </div>
    </form>

</div>
</div>
@endsection
