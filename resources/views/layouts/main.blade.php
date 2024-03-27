<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Alju Shoes Clean | Laundry Sepatu Terbaik se-Jakarta Pusat</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icons/alju-shoes-clean-logo-transparent.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/front-style.css') }}" />
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white py-3 fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">ALJU SHOE</a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    @if (Request::is('/'))
                    <a class="btn btn-light me-3 btn-order my-button" href="#">Order Sekarang</a>
                    @endif
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link active" href="/">Beranda</a>
                        <a class="nav-link" href="#">Layanan</a>
                        <a class="nav-link" href="#">Testimoni</a>
                    </div>
                    @if (Request::is('/'))
                        <a class="btn btn-light btn-order-collapse my-button" href="#">Order Sekarang</a>
                    @endif
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="wrapper">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer mb-5">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-4 col-sm-6">
                        <h6>ALJU SHOE</h6>
                        <p class="mb-3">Merupakan usaha yang bergerak dibidang jasa cuci sepatu yang berasal dari Jakarta. Kami memliki berbagai layanan jenis treatment yang kami tawarkan.</p>
                        <button class="btn btn-outline-my-primary px-4 my-button" style="font-size: .95em">Konsultasi</button>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <h6>Layanan</h6>
                        <li><a href="" class="footer-link">Fast Clean</a></li>
                        <li><a href="" class="footer-link">Deep Clean</a></li>
                        <li><a href="" class="footer-link">Deep Clean Express</a></li>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <h6>Navigasi</h6>
                        <li><a href="" class="footer-link">Beranda</a></li>
                        <li><a href="" class="footer-link">Layanan</a></li>
                        <li><a href="" class="footer-link">Order</a></li>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <h6>Alamat</h6>
                        <p class="mb-2"><a href="" class="footer-link">Jl. Johar Baru Utara 4 No.20 RT.03/RW.09 Jakarta Pusat.</a></p>
                        <a href="" class="footer-link"><i class="bx bxl-whatsapp fs-3"></i></a>
                        <a href="" class="footer-link"><i class="bx bxl-instagram fs-3"></i></a>
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

        {{-- DataTables --}}
        <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>

        {{-- LightBox JS --}}
        <script src="{{ asset('assets/js/bs5-lightbox.js') }}"></script>

        @yield('scripts')
    </body>
</html>

