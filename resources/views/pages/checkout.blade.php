@extends('layouts.app')

@section('content')
<br><br><br><br>

<div class="container">
    <h1 class="display-5">Checkout</h1>
    <hr>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ url('checkout/action') }}" onsubmit="return validateForm()">
        @csrf
        <div class="form-group">
            <label>Nama Pemesan</label>
            <input type="text" class="form-control" name="nama_pesanan" placeholder="Misal: Diky..." required>
        </div>

        <div class="form-group">
            <label>Telepon/Whatsapp</label>
            <input type="number" onchange="cekOperator(this)" class="form-control" name="no_hp_pesanan" placeholder="Misal: 6283..." required>
            <small id="num-error" class="text-danger"></small>
            <small id="num-success" class="text-success"></small>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input type="text" class="form-control" name="alamat_pesanan" placeholder="Misal: Jl. Panarah..." required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email_pesanan" placeholder="Misal: diky@gmail.com..." required>
        </div>
        <div class="row">
            <div class="col">
                <label>Total Pembelian</label>
                <br>
                <h5>Rp. {{ number_format($cartTotal, 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-auto">
            </div>
            <div class="col col-lg-2">
                <label>Total Ongkos Kirim</label>
                <br>
                <h5>Rp. {{ $cartTotal > 100000 ? 0 : '10.000' }}</h5>
                <input type="hidden" name="total" value="{{ $cartTotal }}">
            </div>
        </div>
        <br>
        <div class="form-group">
            <label class="text-info">Gratis ongkos kirim untuk pembelian melebihi Rp 100.000</label>
        </div>

        <div class="row">
            <div class="col">
                <label>Jenis Pembayaran</label>
                <br>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary active">
                        <input type="radio" name="jenis_pembayaran" value="Transfer" id="option1" autocomplete="off" checked>
                        Transfer
                    </label>
                    <label class="btn btn-outline-primary">
                        <input type="radio" name="jenis_pembayaran" value="COD" id="option2" autocomplete="off"> COD
                    </label>
                </div>
            </div>
            <div class="col-md-auto">
            </div>
            <div class="col col-lg-2">
                <label>Total Pembayaran</label>
                <br>
                <h5>Rp. {{ number_format($cartTotal > 100000 ? $cartTotal : $cartTotal + 10000, 0, ',', '.') }}</h5>
                <input type="hidden" name="total" value="{{ $cartTotal }}">
            </div>
        </div>
        <br>
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6Lf_FYYrAAAAAFgs4r8A3Chvw-IPkfkcYH5M98dT"></div>
        </div>

        <div class="row">
            <div class="col">
                <a href="{{ url('cart') }}" class="btn btn-info">Kembali</a>
            </div>
            <div class="col-md-auto">
            </div>
            <div class="col col-lg-2">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button type="submit" class="btn btn-primary">Pesan</button>
            </div>
        </div>
    </form>
    <br><br><br><br>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="{{ asset('assets/js/noTelp.js') }}"></script>
<script>
    let validNum = false;

    function cekOperator(el) {
        const no = el.value;
        const validate = getOperator(no, true)

        const err = document.querySelector('#num-error');
        const succ = document.querySelector('#num-success');

        err.textContent = '';
        succ.textContent = '';

        if (!validate.valid) {
            err.textContent = `Nomor tidak valid (${validate.message}).`
            validNum = false;
            return false;
        }

        succ.textContent = `${validate.operator} (${validate.card})`
        validNum = true;
        return true;
    }

    function validateForm(el) {
        return validNum;
    }
</script>
@endpush
