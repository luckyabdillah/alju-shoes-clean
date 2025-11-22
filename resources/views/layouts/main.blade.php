<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="web_path" content="{{ asset('') }}">
        <title>Alju Shoes Clean | Laundry Sepatu Terbaik se-Jakarta Pusat</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/alju-logo-circle-modified.ico') }}" />
        <link rel="apple-touch-icon" href="{{ asset('assets/img/icons/alju-logo-circle-modified.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/front-style.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}" />
        
        <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap-theme.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}" />

        @stack('styles')
    </head>

    <body>
        <!-- Sweet Alert -->
        @if (session()->has('failed'))
            <div class="flash-data-failed" data-flash="{{ session('failed') }}"></div>
        @endif
        <!-- End Sweet Alert -->

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white py-2 fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold d-none d-md-flex align-items-center gap-2 py-3" href="/">
                    <img src="{{ asset('assets/img/icons/alju-logo-circle.png') }}" alt="Logo" width="38">
                    <span>ALJU SHOES CLEAN</span>
                </a>
                <a class="navbar-brand fw-bold d-block d-md-none" href="/">
                    <img src="{{ asset('assets/img/icons/alju-logo-circle.png') }}" alt="Logo" width="45">
                </a>
                @if (Request::is('/'))
                <a class="btn btn-my-secondary ms-auto btn-order my-button" href="/order" id="btn-order-navbar-mobile">Order Sekarang</a>
                @endif
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link fw-medium" href="/#">Beranda</a>
                        <a class="nav-link fw-medium" href="/#treatment">Layanan</a>
                        <a class="nav-link fw-medium" href="/#testimoni">Testimoni</a>
                        @if (auth()->user())
                            <a class="nav-link fw-medium" href="/dashboard">Dashboard</a>
                        @endif
                    </div>
                    @if (Request::is('/'))
                        <a class="btn btn-my-secondary btn-order-collapse my-button" href="/order" id="btn-order-navbar-desktop">Order Sekarang</a>
                    @endif
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        
        <div class="wrapper">
            @yield('content')
        </div>

        @php
            $whatsappNumber = DB::table('configs')->where('slug', 'nomor-whatsapp')->first();
            $instagramAccount = DB::table('configs')->where('slug', 'akun-instagram')->first();
        @endphp

        <!-- Footer -->
        <footer class="footer mb-5 {{ Request::is('order*') || Request::is('pickup-delivery*') || Request::is('invoice*') ? 'order-page' : '' }}">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-4 col-sm-6 mb-4">
                        <h6>ALJU SHOES CLEAN</h6>
                        <p class="mb-3">Merupakan usaha yang bergerak dibidang jasa cuci sepatu yang berasal dari Jakarta. Kami memliki berbagai layanan jenis treatment yang kami tawarkan.</p>
                        <a href="https://wa.me/{{ $whatsappNumber->value }}" target="_blank" class="btn btn-outline-my-primary px-4 my-button" style="font-size: .95em">Konsultasi</a>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-4">
                        <h6>Navigasi</h6>
                        <li><a href="/#" class="footer-link">Beranda</a></li>
                        <li><a href="/#treatment" class="footer-link">Layanan</a></li>
                        <li><a href="/#testimoni" class="footer-link">Testimoni</a></li>
                        <li><a href="/order" class="footer-link">Order</a></li>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-4">
                        <h6>Alamat</h6>
                        <p class="mb-2"><a href="javascript:void(0)" class="footer-link">Jl. Johar Baru Utara 1 RT.09/RW.03 Jakarta Pusat.</a></p>
                        <a href="https://wa.me/{{ $whatsappNumber->value }}" target="_blank" class="footer-link"><i class="bx bxl-whatsapp fs-3"></i></a>
                        <a href="https://www.instagram.com/{{ $instagramAccount->value }}" target="_blank" class="footer-link"><i class="bx bxl-instagram fs-3"></i></a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <!-- endbuild -->

        <!-- SweetAlertJS -->
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

        <!-- DataTables -->
        <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
        
        <!-- DateRange Picker JS -->
        {{-- <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script> --}}
        
        <!-- Select2 JS -->
        {{-- <script src="{{ asset('assets/js/select2.min.js') }}"></script> --}}

        <!-- LightBox JS -->
        <script src="{{ asset('assets/js/bs5-lightbox.js') }}"></script>
        
        <!-- TypeIt JS -->
        <script src="{{ asset('assets/js/typeit.js') }}"></script>
        
        <!-- OwlCarousel -->
        {{-- <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script> --}}

        <!-- FrontPage JS -->
        <script src="{{ asset('assets/js/front-script.js') }}"></script>

        @yield('scripts')
    </body>
</html>

