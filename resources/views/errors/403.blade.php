<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Opps....</title>
  <!-- plugins:css -->
  <link href="{{ URL::asset('public/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('public/vendors/css/vendor.bundle.base.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('public/vendors/css/vendor.bundle.addons.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ URL::asset('public/vendors/iconfonts/font-awesome/css/font-awesome.css')}}">

 <!-- endinject -->
 <!-- plugin css for this page -->
 <!-- End plugin css for this page -->
 <!-- inject:css -->
 <link rel="stylesheet" href="{{ URL::asset('public/css/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
        <div class="row flex-grow">
          <div class="col-lg-7 mx-auto text-white">
            <div class="row align-items-center d-flex flex-row">
              <div class="col-lg-6 text-lg-right pr-lg-4">
                <h1 class="display-1 mb-0">403</h1>
              </div>
              <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                <h2>SORRY!</h2>
                <h3 class="font-weight-light">The page you’re looking for was not found.</h3>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-center mt-xl-2">
                <a class="text-white font-weight-medium" href="{{URL::to('/')}}">Back to home</a>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 mt-xl-2">
                <p class="text-white font-weight-medium text-center">Copyright BSTravel &copy; {{Carbon\Carbon::now()->year}} All rights reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ URL::asset('public/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{ URL::asset('public/vendors/js/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ URL::asset('public/js/off-canvas.js')}}"></script>
  <script src="{{ URL::asset('public/js/misc.js')}}"></script>
  <!-- endinject -->
</body>

</html>
