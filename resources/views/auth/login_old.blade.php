<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>Login</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/skin-modes.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ url('assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ url('assets/colors/color1.css') }}" />

</head>

<body class="app sidebar-mini ltr login-img">

    <!-- BACKGROUND-IMAGE -->
    <div class="">
        <!-- PAGE -->
        <div class="page bg-dark">
            <div class="">

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="index.html"><img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img" alt=""></a>
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                            <span class="login100-form-title pb-5">
                                Login
                            </span>
                            <div class="panel panel-primary">
                                <div class="panel-body tabs-menu-body p-0 pt-1">
                                    @if(Session::has('error'))
                                    <div class="alert alert-danger">
                                    {{ Session::get('error')}}
                                    </div>
                                    @endif
                                    <div class="tab-content">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="wrap-input100 input-group">
                                                <p class="input-group-text bg-white text-muted">
                                                    <i class="fa fa-user text-muted" aria-hidden="true"></i>
                                                </p>
                                                <input class="input100 border-start-0 form-control ms-0" type="text" placeholder="Username" name="username" id="username" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="wrap-input100 input-group">
                                                <p class="input-group-text bg-white text-muted">
                                                    <i class="fa fa-lock text-muted" aria-hidden="true"></i>
                                                </p>
                                                <input class="input100 border-start-0 form-control ms-0" type="password" placeholder="Password" name="password" id="password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="container-login100-form-btn">
                                                <button type="submit" class="login100-form-btn btn-primary">
                                                    Login
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="{{ url('assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ url('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{ url('assets/js/show-password.min.js') }}"></script>

    <!-- GENERATE OTP JS -->
    <script src="{{ url('assets/js/generate-otp.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ url('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ url('assets/js/themeColors.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ url('assets/js/custom.js') }}"></script>


</body>

</html>