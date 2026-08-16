<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <title>Pesan Online Ai-CHA!</title>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/ai-cha-logo.png') }}" alt="Logo Ai-CHA" height="40" style="filter: none; opacity: 1;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ (request()->is('/') || request()->is('product/*')) ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item {{ request()->is('cart') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('cart') }}">Keranjang</a>
                    </li>
                    <li class="nav-item {{ request()->is('status-pesanan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('status-pesanan') }}">Cek Status</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin') }}">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <div id="footer">
        <br><br>
        <h5 class="text-center">Ai-CHA 2025 | Created by <a href='https://ai-chafood.com/' title='Ai-CHA' target='_blank'>Ai-CHA</a></h5>
        <br><br>
    </div>
    
    <script type="text/javascript">
        function addToCart(product) {
            const isProductInCart = cart.some(item => item.id_barang === product.id_barang);

            if (!isProductInCart) {
                cart.push(product);
                localStorage.setItem('cart', JSON.stringify(cart));
            } else {
                alert('Produk sudah ada di keranjang!')
            }
        }

        function getCartFromLocalStorage() {
            const storedCart = localStorage.getItem('cart');
            return storedCart ? JSON.parse(storedCart) : [];
        }

        const cart = getCartFromLocalStorage();
    </script>
    @stack('scripts')
</body>
</html>
