<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $page_title ?? 'Admin' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/docs/css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/docs/fa/css/font-awesome.min.css') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
</head>
<body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header">
        <a class="app-header__logo" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/navbaraicha.png') }}" alt="Logo Ai-CHA" height="60" style="filter: none; opacity: 1;">
        </a>
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <ul class="app-nav">
            <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="{{ url('admin/logout') }}"><i class="fa fa-sign-out fa-lg"></i> Keluar</a></li>
                </ul>
            </li>
        </ul>
    </header>

    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{ asset('assets/img/admin_icon.png') }}" alt="User Image" width="60px" height="60px">
            <div>
                <p class="app-sidebar__user-name">Yesi Maya</p>
                <p class="app-sidebar__user-designation">Administrator</p>
            </div>
        </div>
        <ul class="app-menu">
            <li><a class="app-menu__item {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('admin') }}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dasbor</span></a></li>
            <li><a class="app-menu__item {{ request()->is('admin/kategori*') ? 'active' : '' }}" href="{{ url('admin/kategori') }}"><i class="app-menu__icon fa fa-list-alt"></i><span class="app-menu__label">Kategori</span></a></li>
            <li><a class="app-menu__item {{ request()->is('admin/produk*') ? 'active' : '' }}" href="{{ url('admin/produk') }}"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Produk</span></a></li>
            <li><a class="app-menu__item {{ request()->is('admin/pesanan*') ? 'active' : '' }}" href="{{ url('admin/pesanan') }}"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Pesanan</span></a></li>
            <li><a class="app-menu__item {{ request()->is('admin/laporan*') ? 'active' : '' }}" href="{{ url('admin/laporan') }}"><i class="app-menu__icon fa fa-file"></i><span class="app-menu__label">Laporan Pesanan</span></a></li>
        </ul>
    </aside>

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> {{ $page_title ?? 'Admin' }}</h1>
                <p>{{ $page_description ?? '' }}</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ $page_title ?? 'Admin' }}</a></li>
            </ul>
        </div>

        @yield('content')
    </main>

    <script src="{{ asset('admin_assets/docs/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('admin_assets/docs/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/docs/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_assets/docs/js/main.js') }}"></script>
    <script src="{{ asset('admin_assets/docs/js/plugins/pace.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
