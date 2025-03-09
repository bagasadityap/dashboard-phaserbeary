<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Pemesanan Gedung UB | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('css')

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @stack('css2')
</head>

<body>
    <style>
        .nav-item {
            font-size: 1.2rem;
            margin-left: 2rem;
        }
        .dropdown-item {
            font-size: 1rem;
        }
    </style>
    {{-- <div class="topbar d-print-none">
        <div class="container-xxl"> --}}
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home.index') }}">
                        <span>
                            <svg width="48px" height="48px" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#22c55e"><path d="M7 9.01L7.01 8.99889" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 9.01L11.01 8.99889" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 13.01L7.01 12.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 13.01L11.01 12.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 17.01L7.01 16.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 17.01L11.01 16.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 21H3.6C3.26863 21 3 20.7314 3 20.4V5.6C3 5.26863 3.26863 5 3.6 5H9V3.6C9 3.26863 9.26863 3 9.6 3H14.4C14.7314 3 15 3.26863 15 3.6V9M15 21H20.4C20.7314 21 21 20.7314 21 20.4V9.6C21 9.26863 20.7314 9 20.4 9H15M15 21V17M15 9V13M15 13H17M15 13V17M15 17H17" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent3" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent3">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ Request::routeIs('home.pemesanan-gedung') || Request::routeIs('home.pemesanan-publikasi') ? 'active' : '' }}" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pemesanan <i class="la la-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('home.pemesanan-gedung') }}">Gedung</a></li>
                                    <li><a class="dropdown-item" href="{{ route('home.pemesanan-publikasi') }}">Publikasi Acara</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{ route('home.pesanan-saya') }}">Pesanan Saya</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="#">Pesanan Saya</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" href="#">FAQ</a>
                            </li>
                        </ul>
                        <ul>
                            <li class="dropdown topbar-item">
                                <a class="nav-link dropdown-toggle arrow-none nav-icon pt-1" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    {{-- <img src="" alt="" class="thumb-lg rounded-circle"> --}}
                                    <i class="las la-user thumb-md rounded-circle bg-light"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                        <div class="flex-shrink-0">
                                            {{-- <img src="" alt="" class="thumb-md rounded-circle"> --}}
                                            <i class="las la-user thumb-md rounded-circle"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                            <h6 class="my-0 fw-medium text-dark fs-13">{{ auth()->user()->name }}</h6>
                                            <small class="text-muted mb-0">{{ auth()->user()->getRoleNames()->first() }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider mt-0 mb-0"></div>
                                    {{-- <a class="dropdown-item" href="pages-profile.html"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                                    <a class="dropdown-item" href="pages-profile.html"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a> --}}
                                    {{-- <div class="dropdown-divider mb-0"></div> --}}
                                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        {{-- </div>
    </div> --}}

    <div class="startbar-overlay d-print-none"></div>

    <div class="page-wrapper">
        <div class="page-content mt-3" style="margin-left: 0 !important;">
            <div class="container-xxl">
                @yield('content')
            </div>

            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        ©
                                        <script> document.write(new Date().getFullYear()) </script>
                                        Direktorat Pengembangan Karir dan Alumni Universitas Brawijaya
                                        {{-- <span
                                            class="text-muted d-none d-sm-inline-block float-end">
                                            Crafted with
                                            <i class="iconoir-heart text-danger"></i>
                                            by Mannatthemes</span> --}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

    @stack('script')

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
