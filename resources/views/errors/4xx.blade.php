@extends('layouts.main')

@section('content')

    <!-- Error -->
    <nav class="my-5 py-5">
        <div class="container my-5 py-5">
            <h1 class="fw-ebold text-my-primary">4XX.</h1>
            <p>Terjadi kesalahan, mohon coba kembali. Redirect otomatis dalam <span id="timer">10</span> detik...</p>
            <a href="/" class="btn btn-my-primary my-button">Kembali ke Home</a>
        </div>
    </nav>
    <!-- End Error -->

@endsection

@section('scripts')
    <script>
        let timer = document.querySelector('#timer')
        let second = parseInt(timer.innerText, 10)

        const timerInterval = setInterval(redirectTimer, 1000);

        function redirectTimer() {
            second -= 1
            timer.innerText = second

            if (second <= 0) {
                clearInterval(timerInterval)
                window.location.href = '/'
            }
        }
    </script>
@endsection