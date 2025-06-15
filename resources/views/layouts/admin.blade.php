<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bank Sampah Kota Padang">
    <meta name="author" content="Alejandro RH">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bank Sampah Kota Padang') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link href="{{ asset('img/logo.png') }}" rel="icon" type="image/png">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
            <div class="sidebar-brand-icon rotate-n-15">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 40px; height: 40px;">
            </div>
            <div class="sidebar-brand-text mx-3">Bank Sampah</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Nav::isRoute('home') }}">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{ __('Dashboard') }}</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            {{ __('Transaksi') }}
        </div>

        @php
            $userRole = auth()->user()->role ?? '';
        @endphp

        @if ($userRole === 'super_admin' || $userRole === 'admin')
            <!-- Nav Item - Sampah -->
            <li class="nav-item {{ Nav::isRoute('sampah.show') }}">
                <a class="nav-link" href="{{ route('sampah.show') }}">
                    <i class="fas fa-fw fa-dumpster"></i>
                    <span>{{ __('Sampah') }}</span>
                </a>
            </li>

            <!-- Nav Item - Order List -->
            <li class="nav-item {{ Nav::isRoute('orderlist.show') }}">
                <a class="nav-link" href="{{ route('orderlist.show') }}">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>{{ __('Order List') }}</span>
                </a>
            </li>
        @endif


        <!-- Nav Item - Setoran -->
        <li class="nav-item {{ Nav::isRoute('setoran') }}">
            <a class="nav-link" href="{{ route('setoran') }}">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>{{ __('Setoran') }}</span>
            </a>
        </li>

        <!-- Nav Item - Tarik Saldo -->
        <li class="nav-item {{ Nav::isRoute('tarik.show') }}">
            <a class="nav-link" href="{{ route('tarik.show') }}">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>{{ __('Tarik Saldo') }}</span>
            </a>
        </li>

        <!-- Nav Item - Riwayat -->
        <li class="nav-item {{ Nav::isRoute('riwayat.show') }}">
            <a class="nav-link" href="{{ route('riwayat.show') }}">
                <i class="fas fa-fw fa-list"></i>
                <span>{{ __('Riwayat') }}</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        
        @php
            $userRole = auth()->user()->role ?? '';
        @endphp


        <!-- Nav Item - Data Nasabah (super_admin & admin) -->
        @if ($userRole === 'super_admin' || $userRole === 'admin')
            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('User') }}
            </div>
            <li class="nav-item {{ Nav::isRoute('nasabah.index') }}">
                <a class="nav-link" href="{{ route('nasabah.index') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Data Nasabah</span>
                </a>
            </li>
        @endif

        <!-- Nav Item - Data Admin (super_admin only) -->
        @if ($userRole === 'super_admin')
            <li class="nav-item {{ Nav::isRoute('admin.index') }}">
                <a class="nav-link" href="{{ route('admin.index') }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Data Admin</span>
                </a>
            </li>
        @endif
        @if ($userRole === 'super_admin' || $userRole === 'admin')
            <!-- Divider -->
            <hr class="sidebar-divider">
         @endif

        <!-- Heading -->
        <div class="sidebar-heading">
            {{ __('Settings') }}
        </div>

        <!-- Nav Item - Profile -->
        <li class="nav-item {{ Nav::isRoute('profile') }}">
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>{{ __('Profile') }}</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                {{-- <!-- Topbar Search -->
                <form method="GET" action="{{ route('search') }}" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> --}}

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                            <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ Auth::user()->name[0] }}"></figure>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('riwayat.show') }}">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Riwayat') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                @yield('main-content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Maintained by <a href="https://github.com/farrazhadyan16" target="_blank">Farraz Hadyan</a>. {{ now()->year }}</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
