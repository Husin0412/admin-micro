<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ionicons/dist/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <!-- custome css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custome.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <!-- custome css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custome.css') }}">
</head>

@if(Session::has('error')) @php $error = Session::get('error'); @endphp @endif
@include('layout.'.config('layout.app_name').'.toast.center')

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
                <div class="row w-100">
                    <div class="col-lg-4 mx-auto">
                        @if(Session::has('expired'))
                        <div class="alert alert-light text-primary" role="alert">
                            {{ Session::get('expired') ?? 'Your session has expired. Please login back..' }}
                        </div>
                        @endif
                        <div class="auto-form-wrapper">
                            <form action="{{ url('login')}}" method="post"> {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="label">Email</label>
                                    <div class="input-group">
                                        <input type="email" name="email"
                                            class="form-control @if(isset($error) && $error->field === 'email') input-error @endif"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                              <i class="mdi mdi-18px mdi-email-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if(isset($error) && $error->field === "email")
                                    <span class="text-danger text-small">
                                        {{ $error->message.' !' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password"
                                            class="form-control @if(isset($error) && $error->field === 'password') input-error @endif"
                                            placeholder="********" required>
                                        <div class="input-group-append" data-name="password">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-18px mdi-eye-off text-secondary"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if(isset($error) && $error->field === "password")
                                    <span class="text-danger text-small">
                                        {{ $error->message.' !' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary submit-btn btn-block">Login</button>
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="form-check form-check-flat mt-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="keep_signed" class="form-check-input" checked>
                                            Keep me signed in </label>
                                    </div>
                                    <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
                                </div>
                            </form>

                        </div>
                        <ul class="auth-footer">
                            <li>
                                <a href="#">Conditions</a>
                            </li>
                            <li>
                                <a href="#">Help</a>
                            </li>
                            <li>
                                <a href="#">Terms</a>
                            </li>
                        </ul>
                        <p class="footer-text text-center">copyright © 2020 <a href="www.microservice.my.id"
                                target="_blank"> www.microservice.my.id </a> All rights reserved.</p>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
    <!-- endinject -->
    <!-- <script src="{{ asset('assets/js/shared/jquery.cookie.js') }}" type="text/javascript"></script> -->
    <script src="{{ asset('assets/js/custome.js') }}"></script>
</body>

</html>