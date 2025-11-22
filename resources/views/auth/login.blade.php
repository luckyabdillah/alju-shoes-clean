<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free" >
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no minimum-scale=1.0, maximum-scale=1.0" />
        
        <title>{{ env('APP_NAME') }} | Login</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/img/favicon/chantha-logo.png') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
    </head>

    <body>
        <section class="login d-flex align-items-center">
            @if (session()->has('failed'))
                <div class="flash-data" data-flash="{{ session('failed') }}"></div>
            @endif
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-md-8">
                        <h1 class="fw-bold text-center pb-3">ALJU SHOES CLEAN</h1>
                        <a href="/" class="d-inline-block pb-1 text-danger">< Kembali</a>
                        <main class="form-signin text-center">
                            <form action="/login" method="POST" id="loginForm">
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
                                <div class="form-floating mb-2">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror mb-0" id="password" placeholder="" required>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="text-start">
                                    <input type="checkbox" id="showPassword" class="form-check-input btn-show">
                                    <label for="showPassword" class="form-check-label">Show password</label>
                                </div>
                                <button class="btn btn-danger w-100 py-2 mt-3 btn-submit" type="submit">Login</button>
                            </form>
                        </main>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core JS -->
        <!-- build: assets/vendor/js/core.js -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        
        <!-- SweetAlertJS -->
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

        <script>
            document.querySelector('#loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const submitButton = document.querySelector('.btn-submit');
                submitButton.setAttribute('disabled', true);
                submitButton.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `
                return this.submit();
            });

            const flashData = $('.flash-data').data('flash');

            if (flashData) {
                Swal({
                    title: 'Failed',
                    text: flashData,
                    type: 'error'
                })
            }

            $(document).on('click', '.btn-show', function(e) {
                if ($(this).prop('checked') == true) {
                    $('#password').attr('type', 'text');
                } else {
                    $('#password').attr('type', 'password');
                }
            });
        </script>

    </body>

</html>

