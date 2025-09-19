<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Point Of Sale</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('foto/logo.png') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/price_rangs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_home/assets/css/style.css') }}">
</head>

<body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset('foto/logo.png') }}" width="100%" alt="">
                </div>
            </div>
        </div>
    </div>
    <header>
        <div class="header-area header-transparrent">
            <div class="headder-top header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-md-9">
                            <div class="logo">
                                <a href="{{ url('/') }}"><img style="height:100px;"
                                        src="{{ asset('foto/logo.png') }}" width="100" alt=""></a>
                            </div>
                        </div>
                        {{-- <div class="col-lg-3 col-md-3">
                            <div class="menu-wrapper">
                                <div class="main-menu">
                                    <nav class="d-none d-lg-block">
                                        <ul id="navigation">
                                            <li><a href="{{ url('/') }}">Home</a></li>
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>

        <div class="slider-area ">
            <div class="slider-active">
                <div class="single-slider slider-height d-flex align-items-center"
                    data-background="foto/bgatas.jpg">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-10 text-center">
                                <div class="hero__caption">

                                    <h1 class="text-warning">Selamat Datang Di<br> Point Of Sale</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="our-services section-pad-t30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-tittle text-center text-white">
                            <span>Point Of Sale</span>
                            <h2>Silahkan Login </h2>
                        </div>

                    </div>
                </div>

            </div>
            <div class="apply-process-area apply-bg pt-150 pb-150 bg-blue" style="background-color: blue !important;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <form action="{{ url('login') }}" class="form-contact contact_form" method="post"
                                novalidate="novalidate">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="mb-2 text-white">Username</label>
                                            <input class="form-control valid"  name="username" type="text"
                                                onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Masukkan Username'"
                                                placeholder="Masukkan Username" required>
                                            @error('username')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="mb-2 text-white">Password</label>
                                            <input class="form-control" name="password" type="password"
                                                onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Masukkan Password'"
                                                placeholder="Masukkan Password" required>
                                            @error('password')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" name="login" value="login"
                                        class="button button-contactForm boxed-btn">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-area footer-bg footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <div class="footer-tittle">
                                    <h4>Tentang</h4>
                                    <div class="footer-pera">
                                        <p>
                                            Point Of Sale.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Info Kontak</h4>
                                <ul>
                                    <li>
                                        <p>Alamat : Jalan Pipa Reja.</p>
                                    </li>
                                    <li><a href="#">Telepon : 088080808</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-area footer-bg">
            <div class="container">
                <div class="footer-border">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-10 col-lg-10 ">
                            <div class="footer-copy-right">
                                <p>
                                    Dibuat Oleh &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    CV Vittindo Digital Teknologi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- All JS Custom Plugins Link Here here -->
    <script src="{{ asset('assets_home/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('assets_home/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/bootstrap.min.js') }}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('assets_home/assets/js/jquery.slicknav.min.js') }}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('assets_home/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/price_rangs.js') }}"></script>

    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('assets_home/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/animated.headline.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.magnific-popup.js') }}"></script>

    <!-- Scrollup, nice-select, sticky -->
    <script src="{{ asset('assets_home/assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.sticky.js') }}"></script>

    <!-- contact js -->
    <script src="{{ asset('assets_home/assets/js/contact.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/mail-script.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/jquery.ajaxchimp.min.js') }}"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="{{ asset('assets_home/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets_home/assets/js/main.js') }}"></script>

</body>

</html>
