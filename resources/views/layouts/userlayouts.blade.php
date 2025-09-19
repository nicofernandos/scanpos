<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('adminasset/assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Point Of Sale')</title>
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
        {{-- <div class="sidebar-overlay" id="sidebarOverlay"></div> --}}
        
        <div class="layout-container">

            <!-- Layout container -->
            <div class="layout-page ">
                <!-- Navbar -->
                {{-- <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar"> --}}
                    <!-- Tombol toggle sidebar -->
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-block">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="toggleSidebar">
                            {{-- <i class="bx bx-menu bx-sm"></i> --}}
                        </a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
</body>
</html>