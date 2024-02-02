<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free" >
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>Dashboard | Alju Shoes Clean System</title>
        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icons/brands/Logo-2-removebg-preview.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

        <!-- Page CSS -->

        <!-- Helpers -->
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>
    </head>

    <body>
        <section class="login d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-md-8 text-center">
                        <img src="{{ asset('assets/img/icons/brands/Logo-2-removebg-preview.png') }}" alt="" class="img-fluid w-50 mb-4">
                        @if (session()->has('loginError'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('loginError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <main class="form-signin text-center">
                            {{-- <h5 class="mb-3 fw-normal">Login</h5> --}}
                            <form action="/login" method="POST">
                                @csrf
                                <div class="form-floating">
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="" required autocomplete="off">
                                    <label for="username">Username</label>
                                    @error('username')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror mb-0" id="password" placeholder="" required>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button class="btn btn-danger w-100 py-2 mt-3" type="submit">Login</button>
                            </form>
                        </main>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Credentials</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li class="mb-3">Operational
                                <ul>
                                    <li>Username: operational</li>
                                    <li>Password: operational</li>
                                </ul>
                            </li>
                            <li class="mb-3">Finance
                                <ul>
                                    <li>Username: finance</li>
                                    <li>Password: finance</li>
                                </ul>
                            </li>
                            <li class="mb-3">Management
                                <ul>
                                    <li>Username: management</li>
                                    <li>Password: management</li>
                                </ul>
                            </li>
                            <li class="mb-3">Administrator
                                <ul>
                                    <li>Username: administrator</li>
                                    <li>Password: administrator</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core JS -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <script>
            $(document).on('click', '.credit', function(e) {
                e.preventDefault();
                $('.modal').modal('show');
            })
        </script>

    </body>

</html>

