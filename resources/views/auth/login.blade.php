<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Perkasa Inovasi Logistik">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/pil/logopilwp.png') }}" />

    <!-- TITLE -->
    <title>Perkasa Inovasi Logistik</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ url('assets/css/login-style.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ url('assets/css/icons.css') }}" rel="stylesheet" />
</head>

<body>
    <div>
        <div class="row xl-12 no-gutters">
            <div class="col-md-6 bg-white p-5 d-flex flex-column align-items-center">
                <img src="{{ url('assets/images/logo/logopilwp.png') }}" style="width:10%;" />
                <h3 class="pb-1">Sign In</h3>
                <p>Welcome back! Please enter your credentials</p>
                @if(Session::has('error'))
                <div class="alert alert-danger">
                {{ Session::get('error')}}
                </div>
                @endif
                <div class="form-style">
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
                        <div class="pb-2">
                            <button type="submit" class="btn btn-primary w-100 font-weight-bold mt-2">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <img src="{{ url('assets/images/logo/login.png') }}" class="img-fluid" style="min-height:100%;" />
            </div>
        </div>
    </div>
</body>

</html>