@extends('layouts.main')

@section('content')
    <!-- Hero -->
    <nav class="hero">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-6 hero-left">
                    <h1 class="fw-ebold">Buat <span style="color: #F04438">Sepatu</span> Kamu Seperti Baru Kembali</h1>
                    <p>Kamu gak perlu repot cuci sepatu sendiri, kami hadir untuk memberikan layanan cuci sepatu premium dengan harga terjangkau dan gak perlu repot ke outlet kami.</p>
                    <a href="javascript:void(0)" class="btn btn-outline-my-primary my-button me-2 px-4">Konsultasi</a>
                    <a href="/order" class="btn btn-my-primary my-button btn-order-sekarang">Order Sekarang</a>
                    <h5 class="mt-4 mb-0 fw-ebold">500+</h5>
                    <h6>Happy Customer</h6>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/icons/hero-image.png') }}" alt="Gambar Sepatu">
                </div>
            </div>
        </div>
    </nav>
    <!-- End Hero -->

    <!-- Why Us -->
    <nav class="why-us">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-6">
                    <img src="" alt="Gambar Sepatu">
                </div>
                <div class="col-md-6">
                    <h3 class="sub-heading fw-bold">Kenapa Harus Pilih Kami?</h3>
                    <div class="my-4">
                        <img src="{{ asset('assets/img/icons/pickup-delivery.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Pickup & Delivery</h6>
                        <p>Tersedia layanan antar jemput, tanpa harus mengunjungi outlet.</p>
                    </div>
                    <div>
                        <img src="{{ asset('assets/img/icons/tepat-waktu.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Tepat Waktu</h6>
                        <p>Pelayanan super cepat dengan menerapkan standar operasional yang sudah teruji.</p>
                    </div>
                    <div>
                        <img src="{{ asset('assets/img/icons/murah-berkualitas.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Murah & Berkualitas</h6>
                        <p>Layanan yang berkualitas dan harga yang super terjangkau.</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Why Us -->

    <!-- Treatment -->
    <nav class="treatment py-1">
        <div class="container">
            <div class="row justify-content-between my-5">
                <h3 class="sub-heading fw-bold mb-4">Treatment</h3>
                <div class="col-md-3">
                    <img src="{{ asset('assets/img/icons/pickup-delivery.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                    <h6 class="fw-ebold d-inline-block mb-3">Fast Clean</h6>
                    <p class="mb-2">Treatment pencucian untuk semua jenis sepatu, pada bagian upper dan midsole secara cepat.</p>
                    <a href="" class="move-link">Lihat selengkapnya <i class='bx bx-right-arrow-alt'></i></a>
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('assets/img/icons/tepat-waktu.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                    <h6 class="fw-ebold d-inline-block mb-3">Deep Clean</h6>
                    <p class="mb-2">Treatment untuk semua jenis sepatu, untuk membersihkan noda secara detail pada seluruh bagian.</p>
                    <a href="" class="move-link">Lihat selengkapnya <i class='bx bx-right-arrow-alt'></i></a>
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('assets/img/icons/murah-berkualitas.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                    <h6 class="fw-ebold d-inline-block mb-3">Deep Clean Express</h6>
                    <p class="mb-2">Treatment pencucian untuk semua jenis sepatu, pada bagian upper dan midsole secara cepat.</p>
                    <a href="" class="move-link">Lihat selengkapnya <i class='bx bx-right-arrow-alt'></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Treatment -->
    
    <!-- Gallery -->
    <nav class="gallery">
        <div class="container my-5">
            <div class="row">
                <h3 class="sub-heading fw-bold mb-4">Gallery</h3>
                <div class="col-md-3 me-4">
                    <img src="{{ asset('assets/img/icons/gallery-1.png') }}" alt="">
                </div>
                <div class="col-md-3 me-4">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('assets/img/icons/gallery-2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <img src="{{ asset('assets/img/icons/gallery-3.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h1 class="fw-ebold">Gak Takut Lagi Buat Pergi Kalo Sepatu Masih Kotor!</h1>
                    <p class="fw-semibold">Kami siap untuk bantu kamu kapan aja dan gak perlu datang ke outlet kami, cukup order melalui online dan kami akan datang tepat waktu dan kebersihan sepatu kamu kami jamin.</p>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Gallery -->

    <!-- Testimoni -->
    <nav class="testimoni">
        <div class="container my-5">
            <div class="row">
                <h3 class="sub-heading fw-bold mb-4">Testimoni</h3>
                <div class="col-md-8">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-5 image">
                                        <img src="{{ asset('assets/img/icons/testimoni-1.png') }}" class="d-inline w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial">
                                        <p class="mb-2">“Gue suka sama hasil sepatunya bersih, kayak beli baru, padahal sepatu gue udah 2 tahun yang lalu. Mantaps sih!!”</p>
                                        <h6 class="fw-bold">Eki Gutawa 1</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-md-5 image">
                                        <img src="{{ asset('assets/img/icons/testimoni-1.png') }}" class="d-inline w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial">
                                        <p class="mb-2">“Gue suka sama hasil sepatunya bersih, kayak beli baru, padahal sepatu gue udah 2 tahun yang lalu. Mantaps sih!!”</p>
                                        <h6 class="fw-bold">Eki Gutawa 2</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-md-5 image">
                                        <img src="{{ asset('assets/img/icons/testimoni-1.png') }}" class="d-inline w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial">
                                        <p class="mb-2">“Gue suka sama hasil sepatunya bersih, kayak beli baru, padahal sepatu gue udah 2 tahun yang lalu. Mantaps sih!!”</p>
                                        <h6 class="fw-bold">Eki Gutawa 3</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="carousel-control">
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            {{-- <span class="carousel-control-prev-icon" aria-hidden="true"></span> --}}
                            {{-- <span class="">Previous</span> --}}
                            <i class='bx bx-chevron-left text-my-primary'></i>
                            {{-- <span>&#8249;</span> --}}
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            {{-- <span class="carousel-control-next-icon" aria-hidden="true"></span> --}}
                            {{-- <span class="">Next</span> --}}
                            <i class='bx bx-chevron-right text-my-primary'></i>
                            {{-- <span>&#8250;</span> --}}
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-start">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="indicators active me-1" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" class="indicators" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" class="indicators" aria-label="Slide 3"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Testimoni -->

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/front-script.js') }}"></script>
@endsection