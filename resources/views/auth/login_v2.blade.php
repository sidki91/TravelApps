<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login System</title>
    <link href="{{ URL::asset('public/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('public/vendors/css/vendor.bundle.base.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('public/vendors/css/vendor.bundle.addons.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('public/vendors/iconfonts/font-awesome/css/font-awesome.css')}}">
   <!-- endinject -->
   <!-- plugin css for this page -->
   <!-- End plugin css for this page -->
   <!-- inject:css -->
   <link rel="stylesheet" href="{{ URL::asset('public/css/style.css')}}">

<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <center>
            <img src="{{ URL::asset('public/images/logo_company.png')}}" width="180" >
          </center>
            <div class="auto-form-wrapper">
              <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                  {{ csrf_field() }}
                <div class="form-group">
                  <div class="input-group">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block" type="submit">Login</button>
                </div>
                <div class="text-block text-center my-3">
                <p class="footer-text text-center" style="color:black">copyright © {{date('Y')}} BSTravel. All rights reserved.</p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
<script src="/js/app.js"></script>
</body>
</html>
