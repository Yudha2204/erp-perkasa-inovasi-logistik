<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Perkasa Inovasi Logistik">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>@yield('title')</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/skin-modes.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ url('assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ url('assets/colors/color1.css') }}" />
    @stack('styles')
</head>

<body class="app sidebar-mini ltr light-mode">
    @include('sweetalert::alert')

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="index.html">
                            <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img light-logo1"
                                alt="logo">
                        </a>
                        <!-- LOGO -->
                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <!-- SEARCH -->
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                    <div class="d-flex order-lg-2">
                                        <div class="dropdown d-lg-none d-flex">
                                            <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                                <i class="fe fe-search"></i>
                                            </a>
                                            <div class="dropdown-menu header-search dropdown-menu-start">
                                                <div class="input-group w-100 p-2">
                                                    <input type="text" class="form-control" placeholder="Search....">
                                                    <div class="input-group-text btn btn-primary">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
                                        <div class="d-flex country">
                                            <!-- <a href="{{ route('setting.index') }}" class="nav-link icon" data-bs-toggle="dropdown"><i
                                                class="fe fe-settings"></i>
                                            </a> -->
                                            <a href="{{ route('setting.index') }}" class="nav-link icon"><i
                                                class="fe fe-settings"></i>
                                            </a>
                                        </div>
                                        @endif
                                        <!-- Theme-Layout -->
                                        <div class="dropdown  d-flex notifications">
                                            <a class="nav-link icon" data-bs-toggle="dropdown"><i
                                                    class="fe fe-bell"></i><span class=" pulse"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading border-bottom">
                                                    <div class="d-flex">
                                                        <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="notifications-menu" id="list-notif">
                                                    {{-- <a class="dropdown-item d-flex" href="notify-list.html">
                                                        <div class="me-3 notifyimg  bg-secondary brround box-shadow-secondary">
                                                            <i class="fe fe-check-circle"></i>
                                                        </div>
                                                        <div class="mt-1 wd-80p">
                                                            <h5 class="notification-label mb-1">Project has been
                                                                approved</h5>
                                                            <span class="notification-subtext">2 hours ago</span>
                                                        </div>
                                                    </a>
                                                    <a class="dropdown-item d-flex" href="notify-list.html">
                                                        <div class="me-3 notifyimg  bg-success brround box-shadow-success">
                                                            <i class="fe fe-shopping-cart"></i>
                                                        </div>
                                                        <div class="mt-1 wd-80p">
                                                            <h5 class="notification-label mb-1">Your Product Delivered
                                                            </h5>
                                                            <span class="notification-subtext">30 min ago</span>
                                                        </div>
                                                    </a>
                                                    <a class="dropdown-item d-flex" href="notify-list.html">
                                                        <div class="me-3 notifyimg bg-pink brround box-shadow-pink">
                                                            <i class="fe fe-user-plus"></i>
                                                        </div>
                                                        <div class="mt-1 wd-80p">
                                                            <h5 class="notification-label mb-1">Friend Requests</h5>
                                                            <span class="notification-subtext">1 day ago</span>
                                                        </div>
                                                    </a> --}}
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a href="{{ route('notifications') }}"
                                                    class="dropdown-item text-center p-3 text-muted">View all
                                                    Notification</a>
                                            </div>
                                        </div>
                                        <!-- NOTIFICATIONS -->
                                        <div class="dropdown d-flex profile-1">
                                            <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                                <img src="{{ url('assets/images/users/21.jpg') }}" alt="profile-user"
                                                    class="avatar  profile-user brround cover-image">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading">
                                                    <div class="text-center">
                                                        <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->name }}</h5>
                                                    </div>
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a class="dropdown-item" href="">
                                                    <i class="dropdown-icon fe fe-user"></i> Profile
                                                </a>
                                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                    <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-Header -->

            <!--APP-SIDEBAR-->
            @include('components.sidebar')
            <!--/APP-SIDEBAR-->

            <!--app-content open-->
            @yield('content')
            <!--app-content closed-->
        </div>
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ url('assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ url('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- INPUT MASK JS-->
    <script src="{{ url('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- SIDEBAR JS -->
    <script src="{{ url('assets/plugins/sidebar/sidebar.js') }}"></script>

    <!-- SIDE-MENU JS -->
    <script src="{{ url('assets/plugins/sidemenu/sidemenu.js') }}"></script>

	<!-- TypeHead js -->
	<script src="{{ url('assets/plugins/bootstrap5-typehead/autocomplete.js') }}"></script>
    <script src="{{ url('assets/js/typehead.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ url('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <script src="{{ url('assets/plugins/p-scroll/pscroll.js') }}"></script>
    <script src="{{ url('assets/plugins/p-scroll/pscroll-1.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ url('assets/js/themeColors.js') }}"></script>

    <!-- Sticky js -->
    <script src="{{ url('assets/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ url('assets/js/custom.js') }}"></script>

    <script>
        const container = document.getElementById('list-notif');
        container.innerHTML = ''

        $.ajax({
            url: "{{ route('notification') }}",
            method: 'GET',
            success: function(response) {
                if(response.message === "Success") {
                    response.data.forEach(el => {
                        const newNotif = document.createElement('a');
                        newNotif.classList.add('dropdown-item')
                        newNotif.classList.add('d-flex')
                        
                        const type = el.type
                        let button = "bg-info"
                        if(type === "info-due-date") {
                            button = "bg-warning"
                        } else if(type === "info-over-due") {
                            button = "bg-danger"
                        }
                        const newContentNotif = `
                        <div class="me-3 notifyimg ${button} brround box-shadow-primary">
                            <i class="fe fe-mail"></i>
                        </div>
                        <div class="mt-1 wd-80p">
                            <h5 class="notification-label mb-1">${el.content}
                            </h5>
                            <span class="notification-subtext">${el.date}</span>
                        </div>
                        `;

                        newNotif.innerHTML = newContentNotif;
                        container.appendChild(newNotif);
                    });
                }
            }
        })
    </script>

    @stack('scripts')
</body>

</html>