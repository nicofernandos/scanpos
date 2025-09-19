<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('adminasset/assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Dashboard - Admin Panel')</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('foto/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Custom CSS untuk sidebar toggle -->
    <style>
        /* Override default layout behavior */
        .layout-wrapper.layout-content-navbar .layout-container {
            padding-left: 0 !important;
        }
        
        /* Sidebar tersembunyi secara default */
        .layout-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            position: fixed !important;
            z-index: 1051;
            width: 260px;
            height: 100vh;
            left: 0;
            top: 0;
        }

        /* Sidebar muncul ketika memiliki class 'show' */
        .layout-menu.show {
            transform: translateX(0);
        }

        /* Overlay gelap ketika sidebar terbuka */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Layout page full width */
        .layout-page {
            margin-left: 0 !important;
            width: 100% !important;
            min-height: 100vh;
        }

        /* Container full width */
        .layout-container {
            width: 100% !important;
            padding-left: 0 !important;
        }

        /* Navbar full width */
        .layout-navbar {
            left: 0 !important;
            width: 100% !important;
            margin-left: 0 !important;
        }

        /* Content wrapper full width */
        .content-wrapper {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* Override template default styles */
        @media (min-width: 1200px) {
            .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container {
                padding-left: 0 !important;
            }
            
            .layout-wrapper.layout-content-navbar .layout-page {
                padding-left: 0 !important;
                margin-left: 0 !important;
            }
        }

        /* Menghilangkan tombol biru template default */
        .layout-menu-toggle.d-xl-none,
        .btn.btn-primary.menu-toggle {
            display: none !important;
        }

        /* Pastikan toggle button terlihat */
        .layout-menu-toggle.d-block {
            display: flex !important;
        }

        /* Additional fixes */
        body.layout-menu-fixed .layout-wrapper:not(.layout-without-menu) .layout-container .layout-page {
            padding-left: 0 !important;
        }

        /* Remove any default menu spacing */
        .layout-wrapper.layout-navbar-fixed .layout-container {
            padding-left: 0 !important;
        }
    </style>

    <!-- Page CSS -->
    @yield('styles')

    <!-- Helpers -->
    <script src="{{ asset('adminasset/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('adminasset/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <!-- Sidebar overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <div class="layout-container">
            <!-- Menu - Sidebar yang tersembunyi -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ url('/dashboard') }}" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bolder text-uppercase ms-2">POS</span>
                    </a>

                    <!-- Tombol close sidebar -->
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none" id="closeSidebar">
                        {{-- <i class="bx bx-x bx-sm align-middle "></i> --}}
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    @if(Auth::user()->role == 'Admin')
                    <!-- Data Management -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Management</span></li>
                    <li class="menu-item {{ request()->is('produk*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-box"></i>
                            <div data-i18n="produk">Kelola Produk</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahproduk') ? 'active' : '' }}">
                            <a href="{{ url('tambahproduk') }}" class="menu-link">
                                <div data-i18n="Tambah Barang">Tambah Produk</div>
                            </a>
                            </li>
                            <li class="menu-item {{ request()->is('produk') ? 'active' : '' }}">
                            <a href="{{ url('produk') }}" class="menu-link">
                                <div data-i18n="Daftar Produk">Daftar Produk</div>
                            </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('kategori*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-note"></i>
                            <div data-i18n="kategori">Kategori</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahkategori') ? 'active' : '' }}">
                                <a href="{{ url('tambahkategori') }}" class="menu-link">
                                    <div data-i18n="Tambah Kategori">Tambah Kategori</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('kategori') ? 'active' : '' }}">
                                <a href="{{ url('kategori') }}" class="menu-link">
                                    <div data-i18n="Kategori">Daftar Kategori</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('stok*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-gift"></i>
                            <div data-i18n="stok">Kelola Stok</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahstok') ? 'active' : '' }}">
                                <a href="{{ url('tambahstok') }}" class="menu-link">
                                    <div data-i18n="Tambah Stok">Tambah Stok</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('stok') ? 'active' : '' }}">
                                <a href="{{ url('stok') }}" class="menu-link">
                                    <div data-i18n="Daftar Stok">Daftar Stok</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('sale order*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-notepad"></i>
                            <div data-i18n="Sale Order">Sale Order</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('saleordertambah') ? 'active' : '' }}">
                                <a href="{{ url('saleordertambah') }}" class="menu-link">
                                    <div data-i18n="Sale Order Tambah">Tambah Sale Order</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('saleorder') ? 'active' : '' }}">
                                <a href="{{ url('saleorder') }}" class="menu-link">
                                    <div data-i18n="Daftar Order">Daftar Sale Order</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('penjualan*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-book"></i>
                            <div data-i18n="Penjualan">Kelola Penjualan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('penjualantambah') ? 'active' : '' }}">
                                <a href="{{ url('penjualantambah') }}" class="menu-link">
                                    <div data-i18n="Penjualan Tambah">Tambah Penjualan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('penjualan') ? 'active' : '' }}">
                                <a href="{{ url('penjualan') }}" class="menu-link">
                                    <div data-i18n="penjualan">Daftar Penjualan</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('pembelian*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-book-alt"></i>
                            <div data-i18n="Pemesanan">Kelola Pembelian</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('pembeliantambah') ? 'active' : '' }}">
                                <a href="{{ url('pembeliantambah') }}" class="menu-link">
                                    <div data-i18n="Pembelian Tambah">Tambah Pembelian</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('pembelian') ? 'active' : '' }}">
                                <a href="{{ url('pembelian') }}" class="menu-link">
                                    <div data-i18n="Daftar Pemesanan">Daftar Pembelian</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('pelanggan*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="pelanggan">Pelanggan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahpelanggan') ? 'active' : '' }}">
                                <a href="{{ url('tambahpelanggan') }}" class="menu-link">
                                    <div data-i18n="Tambah Pelanggan">Tambah Pelanggan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('pelanggan') ? 'active' : '' }}">
                                <a href="{{ url('pelanggan') }}" class="menu-link">
                                    <div data-i18n="Daftar tamu">Daftar Pelanggan</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @endif
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page ">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <!-- Tombol toggle sidebar -->
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-block">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="toggleSidebar">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('adminasset/assets/img/avatars/profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('adminasset/assets/img/avatars/profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Keluar</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <script>document.write(new Date().getFullYear());</script>, Point Of Sale
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('adminasset/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('adminasset/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('adminasset/assets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleSidebar');
            const closeButton = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('layout-menu');
            const overlay = document.getElementById('sidebarOverlay');

            function showSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden'; 
            }
            function hideSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = ''; 
            }

            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (sidebar.classList.contains('show')) {
                    hideSidebar();
                } else {
                    showSidebar();
                }
            });

            closeButton.addEventListener('click', function(e) {
                e.preventDefault();
                hideSidebar();
            });

            overlay.addEventListener('click', function() {
                hideSidebar();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    hideSidebar();
                }
            });

        
            const menuLinks = sidebar.querySelectorAll('.menu-link:not(.menu-toggle)');
            menuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    setTimeout(hideSidebar,0);
                });
            });
            const templateMenuToggles = document.querySelectorAll('.layout-menu-toggle:not(#toggleSidebar):not(#closeSidebar)');
            templateMenuToggles.forEach(function(toggle) {
                toggle.style.display = 'none';
            });
        });
    </script>
    @yield('script')
</body>
</html>