<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/docs/css/main.css') }}">
  <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/docs/fa/css/font-awesome.min.css') }}">
  <title>Login - Pesan Online Ai-CHA! Admin</title>
</head>
<body>
  <section class="material-half-bg" style="background-color: white;">
    <div class="cover"></div>
  </section>

  <style>
    .material-half-bg .cover {
      background-color: #e53935 !important;
    }
  </style>

  <section class="login-content">

    <div class="login-box">
      <form class="login-form" action="{{ url('admin/authenticate') }}" method="POST">
        @csrf
        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>LOGIN</h3>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="form-group">
          <label class="control-label">USERNAME</label>
          <input class="form-control" name="username" type="text" placeholder="Username" autofocus>
        </div>
        <div class="form-group">
          <label class="control-label">PASSWORD</label>
          <input class="form-control" name="password" type="password" placeholder="Password">
        </div>

        <div class="form-group btn-container">
          <button type="submit" class="btn btn-danger btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>MASUK</button>
        </div>
      </form>
    </div>
    <center><br>
      <p>Created by <a href='https://ai-chafood.com/' title='Ai-CHA' target='_blank' style="color: red;">Ai-CHA</a></p>
    </center>
  </section>
  <script src="{{ asset('admin_assets/docs/js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('admin_assets/docs/js/popper.min.js') }}"></script>
  <script src="{{ asset('admin_assets/docs/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('admin_assets/docs/js/main.js') }}"></script>
  <script src="{{ asset('admin_assets/docs/js/plugins/pace.min.js') }}"></script>
</body>
</html>
