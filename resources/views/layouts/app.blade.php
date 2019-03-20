<!DOCTYPE html>
<html lang="en">


@include('layouts.header')
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
  @include('layouts.navigation')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
  @include('layouts.slidebar')
      <!-- partial -->
      @if(Request::segment(1)=='home')
      <div class="main-panel">
      <div class="content-wrapper">
      <div class="row">
          <div class="col-12">
         @yield('content')
  </div>
      </div>
    </div>
  </div>
      @else
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      @if($__env->yieldContent('menu'))
            <h4 class="card-title">@yield('menu')</h4>
          @endif
            @yield('content')


        </div>
          </div>
            </div>
              </div>
                </div>
                  </div>
                    </div>
                      </div>
                      @endif
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layouts.footer')

</body>

</html>
