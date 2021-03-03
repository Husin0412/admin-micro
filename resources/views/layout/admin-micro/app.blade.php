<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ isset($page_title) ? 'admin-micro | '.($page_title) : 'admin-micro' }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ionicons/dist/css/ionicons.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <!-- pnotify css -->
    <link rel="stylesheet" href="{{ asset('assets/css/notify/pnotify.css') }}">
    <!-- custome css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custome.css') }}">
    <!-- datatable css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <!-- End Layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    @yield('content_style')

</head>

<div id="loading"></div>
<body>
    <div class="container-scroller">
        <!-- _navbar -->
        @include('layout.'.config('layout.app_name').'.partials._navbar')
        <div class="container-fluid page-body-wrapper">
            <!-- _sidebar -->
            @include('layout.'.config('layout.app_name').'.partials._sidebar')
            <div class="main-panel">
                <div class="card">
                    <div class="card-body">
                        <div class="row w-100">
                            <div class="col-lg-6">
                                <h4 class="card-title"> @if(isset($page_title)) {{ $page_title }} @endif</h4>
                            </div>
                            <div class="col-lg-6 p-0 m-0">
                                @include('layout.'.config('layout.app_name').'.partials.action.index')
                            </div>
                        </div>
                        <p class="card-description mt-4"> </p>
                        @yield('content_body')
                    </div>
                </div>
                <!-- -- -->
                @include('layout.'.config('layout.app_name').'.toast.modal')
                @include('layout.'.config('layout.app_name').'.toast.right')
                <!-- _footer -->
                @include('layout.'.config('layout.app_name').'.partials._footer')
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/demo_1/dashboard.js') }}"></script>
    <!-- End custom js for this page-->
    <!-- <script src="{{ asset('assets/js/shared/jquery.cookie.js') }}" type="text/javascript"></script> -->
    <!-- custome -->
    <script src="{{ asset('assets/js/notify/pnotify.js') }}"></script>
    <!-- custome -->
    <script src="{{ asset('assets/js/custome.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('assets/js/datatable.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('.data-table').DataTable()
    })
    </script>
    @yield('content_script')
</body>

</html>