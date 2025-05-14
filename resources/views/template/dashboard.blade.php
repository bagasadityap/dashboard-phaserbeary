<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Pemesanan Gedung UB | {{ $page }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mobius1-selectr/selectr.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/block-ui.js') }}"></script>

    @stack('css')

</head>

<body>
    <div class="topbar d-print-none">
        <div class="container-xxl">
            <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li>
                        <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                            <i class="iconoir-menu-scale"></i>
                        </button>
                    </li>
                    <li class="mx-3 welcome-text">
                        @if ($page == 'Dashboard')
                            <h3 class="mb-0 fw-bold text-truncate">Selamat Datang, Admin!</h3>
                        @else
                            <h4 class="mb-0 fw-bold text-truncate">\ {{ $page }} </h4>
                        @endif
                    </li>
                </ul>
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li class="hide-phone app-search pe-5">
                        <div class="d-flex justify-content-between fs-5 fw-semibold">
                            <div class="d-flex justify-between bg-secondary p-2 rounded-start" id="time">
                            </div>
                            <div class="date bg-white p-2 rounded-end" id="date">
                            </div>
                        </div>

                        <script>
                            function updateTimeAndDate() {
                                const now = new Date();
                                const hours = (now.getHours() < 10 ? '0' : '') + now.getHours();
                                const minutes = (now.getMinutes() < 10 ? '0' : '') + now.getMinutes();
                                document.getElementById("time").textContent = `${hours}:${minutes}`;

                                const day = now.getDate();
                                const month = now.toLocaleString('default', { month: 'short' });
                                const year = now.getFullYear();
                                document.getElementById("date").textContent = `${day} ${month} ${year}`;
                            }

                            updateTimeAndDate();
                            setInterval(updateTimeAndDate, 1000);
                        </script>
                    </li>

                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="las la-user thumb-md rounded-circle"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                <div class="flex-shrink-0">
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
            </nav>
        </div>
    </div>

    <div class="startbar d-print-none">
        <div class="brand">
            <a href="{{ route('dashboard.index') }}" class="logo">
                <span>
                    <svg width="36px" height="36px" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#22c55e"><path d="M7 9.01L7.01 8.99889" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 9.01L11.01 8.99889" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 13.01L7.01 12.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 13.01L11.01 12.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 17.01L7.01 16.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 17.01L11.01 16.9989" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 21H3.6C3.26863 21 3 20.7314 3 20.4V5.6C3 5.26863 3.26863 5 3.6 5H9V3.6C9 3.26863 9.26863 3 9.6 3H14.4C14.7314 3 15 3.26863 15 3.6V9M15 21H20.4C20.7314 21 21 20.7314 21 20.4V9.6C21 9.26863 20.7314 9 20.4 9H15M15 21V17M15 9V13M15 13H17M15 13V17M15 17H17" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            </a>
        </div>

        <div class="startbar-menu" >
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <ul class="navbar-nav mb-auto w-100">
                        @can('Dashboard')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.index') }}">
                                <i class="iconoir-home-simple menu-icon"></i>
                                <span>Dashboards</span>
                            </a>
                        </li>
                        @endcan
                        @can('Gedung')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gedung.index') }}">
                                <i class="iconoir-building menu-icon"></i>
                                <span>Gedung</span>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            @can('Pesanan')
                            <a class="nav-link" href="#sidebarpesanan" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarPesanan">
                                <i class="iconoir-data-transfer-both menu-icon"></i>
                                <span>Pesanan</span>
                            </a>
                            @endcan
                            <div class="collapse " id="sidebarpesanan">
                                <ul class="nav flex-column">
                                    @can('Pesanan Gedung-Pesanan')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('pesanan.gedung.index') }}">Gedung</a>
                                    </li>
                                    @endcan
                                    @can('Pesanan Publikasi Acara-Pesanan')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('pesanan.publikasi.index') }}">Publikasi Acara</a>
                                    @endcan
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            @can('Configuration')
                            <a class="nav-link" href="#sidebarConfiguration" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarConfiguration">
                                <i class="iconoir-settings menu-icon"></i>
                                <span>Configuration</span>
                            </a>
                            @endcan
                            <div class="collapse " id="sidebarConfiguration">
                                <ul class="nav flex-column">
                                    @can('User-Configuration')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('configuration.user.index') }}">User</a>
                                    </li>
                                    @endcan
                                    @can('Role-Configuration')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('configuration.role.index') }}">Role & Permission</a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="startbar-overlay d-print-none"></div>

    <div class="page-wrapper">
        <div class="page-content">
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

    <!-- Javascript  -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    @stack('script')
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
</body>

</html>
