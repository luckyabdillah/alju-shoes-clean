@extends('layouts.main')

@section('content')

    <!-- Hero -->
    <nav class="hero">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-6 hero-left">
                    <h1 class="fw-ebold">Buat <span class="item" style="color: #F04438;"></span> Kamu Seperti Baru Kembali</h1>
                    <p>Kamu gak perlu repot cuci sepatu sendiri, kami hadir untuk memberikan layanan cuci sepatu premium dengan harga terjangkau dan gak perlu repot ke outlet kami.</p>
                    <a href="https://wa.me/{{ $whatsappNumber->value }}" target="_blank" class="btn btn-outline-my-primary my-button me-2 px-4">Konsultasi</a>
                    <a href="/order" class="btn btn-my-primary my-button" id="btn-order-hero">Order Sekarang</a>
                    <h5 class="mt-4 mb-0 fw-ebold counter">&nbsp;</h5>
                    <h6>Happy Customer</h6>
                </div>
                <div class="col-md-6 text-center">
                    @if ($campaigns->count() == 1)
                        <img src="{{ asset('storage') . '/' . $campaigns[0]->img }}" class="img-fluid ms-auto" alt="{{ $campaigns[0]->name }}">
                    @else
                        <div id="carouselExampleIndicators1" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach ($campaigns as $campaign)
                                    @if ($loop->index == 0)
                                        <button type="button" data-bs-target="#carouselExampleIndicators1" data-bs-slide-to="{{ $loop->index }}" class="active" aria-current="true" aria-label="Slide {{ $loop->iteration }}"></button>
                                    @else
                                        <button type="button" data-bs-target="#carouselExampleIndicators1" data-bs-slide-to="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                                    @endif
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($campaigns as $campaign)
                                    @if ($loop->index == 0)
                                        <div class="carousel-item active">
                                            <img src="{{ asset("storage/$campaign->img") }}" class="img-fluid ms-auto" alt="{{ $campaign->name }}">
                                        </div>
                                    @else
                                        <div class="carousel-item">
                                            <img src="{{ asset("storage/$campaign->img") }}" class="img-fluid ms-auto" alt="{{ $campaign->name }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <!-- End Hero -->

    <!-- Why Us -->
    <nav class="why-us">
        <div class="container">
            {{-- <div class="row row-gap-3">
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/icons/why-us.png') }}" alt="Gambar Sepatu" class="img-fluid">
                </div>
                <div class="col-md-6 my-auto">
                    <h3 class="sub-heading fw-bold">Kenapa Harus Pilih Kami?</h3>
                    <div class="my-4">
                        <img src="{{ asset('assets/img/icons/pickup-delivery.png') }}" alt="Pickup & Delivery" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Pickup & Delivery</h6>
                        <p>Tersedia layanan antar jemput, tanpa harus mengunjungi outlet.</p>
                    </div>
                    <div>
                        <img src="{{ asset('assets/img/icons/tepat-waktu.png') }}" alt="Tepat Waktu" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Tepat Waktu</h6>
                        <p>Pelayanan super cepat dengan menerapkan standar operasional yang sudah teruji.</p>
                    </div>
                    <div>
                        <img src="{{ asset('assets/img/icons/murah-berkualitas.png') }}" alt="Murah & Berkualitas" width="30px" style="margin-top: -3px">
                        <h6 class="fw-ebold d-inline-block mb-3">Murah & Berkualitas</h6>
                        <p>Layanan yang berkualitas dan harga yang super terjangkau.</p>
                    </div>
                </div>
            </div> --}}
            <h3 class="sub-heading mt-3 fw-bold">Kenapa Harus Pilih Kami?</h3>
            <div class="row mt-2 pb-5 g-3">
                <div class="col-lg col-6">
                    <div class="card rounded-4 border-danger h-100">
                        <div class="card-body">
                            <i class="bx bx-badge-check bx-md bx-border-circle bg-my-primary text-white"></i>
                            <h6 class="fs-4 fw-bold mt-2 pt-1">Ditangani oleh Ahli</h6>
                            <p class="m-0">Sepatu kesayanganmu dirawat oleh tenaga professional berpengalaman, dengan teknik terbaik sesuai jenis bahan dan kondisi sepatu.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-6">
                    <div class="card rounded-4 border-danger h-100">
                        <div class="card-body">
                            <i class="bx bx-support bx-md bx-border-circle bg-my-primary text-white"></i>
                            <h6 class="fs-4 fw-bold mt-2 pt-1">Layanan Customer Service</h6>
                            <p class="m-0">Tim kami selalu siap membantu dengan pelayanan cepat, ramah, dan responsif untuk menjawab semua pertanyaan dan kebutuhanmu.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-6">
                    <div class="card rounded-4 border-danger h-100">
                        <div class="card-body">
                            <i class="bx bx-package bx-md bx-border-circle bg-my-primary text-white"></i>
                            <h6 class="fs-4 fw-bold mt-2 pt-1">Free Pickup & Delivery</h6>
                            <p class="m-0">Nikmati kenyamanan Free Pickup & Delivery! Kami akan menjemput dan mengantar sepatu kamu tanpa perlu keluar rumah. Gratis untuk jarak hingga {{ $freeDelivery->value }} km.
                        </div>
                    </div>
                </div>
                <div class="col-lg col-6">
                    <div class="card rounded-4 border-danger h-100">
                        <div class="card-body">
                            <i class="bx bx-store bx-md bx-border-circle bg-my-primary text-white"></i>
                            <h6 class="fs-4 fw-bold mt-2 pt-1">Drop Zone Area</h6>
                            <p class="m-0">Serahkan sepatu kamu di Drop Zone terdekat! Pilih lokasi yang nyaman, tinggalkan sepatu, dan kami yang akan merawatnya. Mudah dan cepat.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-12">
                    <div class="card rounded-4 border-danger h-100">
                        <div class="card-body">
                            <i class="bx bx-check-shield bx-md bx-border-circle bg-my-primary text-white"></i>
                            <h6 class="fs-4 fw-bold mt-2 pt-1">Jaminan Garansi Layanan</h6>
                            <p class="m-0">Kami memberikan jaminan garansi untuk setiap layanan kami. Jika ada kerusakan akibat proses perawatan, akan kami ganti sesuai tingkat kerusakannya.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Why Us -->

    <!-- Treatment -->
    <nav class="treatment pt-5 py-3 mt-5" id="treatment">
        <div class="container">
            <h3 class="sub-heading fw-bold mt-4 mb-5">Treatment</h3>
            <div class="horizontal-scroll">
                <div class="treatment-list">
                    @foreach ($treatments as $treatment)
                        <div class="treatment-item text-center" data-uuid="{{ $treatment->uuid }}" style="cursor: pointer;">
                            <img src="{{ asset("storage/$treatment->photo") }}" alt="treatment icon" class="rounded-circle m-auto" style="width: 70px !important;">
                            <span class="d-block mt-2">{{ $treatment->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center mt-3 mb-4 treatment-indicator text-my-primary">
                <i class='bx bx-chevron-left fs-3 prev-button' style="cursor: pointer"></i>
                <i class='bx bx-chevron-right fs-3 next-button' style="cursor: pointer"></i>
            </div>
        </div>
    </nav>
    <!-- End Treatment -->
    
    <!-- Gallery -->
    <nav class="gallery">
        <div class="container mt-5">
            <div class="row g-4">
                <h3 class="sub-heading fw-bold mb-4">Gallery</h3>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-6">
                            <img src="{{ asset('storage/' . $gallery1->img) }}" alt="" class="w-100 h-100 img-gallery">
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-auto">
                                    <img src="{{ asset('storage/' . $gallery2->img) }}" alt="" class="w-100 h-100 img-gallery">
                                </div>
                                <div class="">
                                    <img src="{{ asset('storage/' . $gallery3->img) }}" alt="" class="w-100 h-100 img-gallery">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="text-75">
                        <h1 class="fw-ebold">Gak Takut Lagi Buat Pergi Kalo Sepatu Masih Kotor!</h1>
                        <p class="fw-semibold">Kami siap untuk bantu kamu kapan aja dan gak perlu datang ke outlet kami, cukup order melalui online dan kami akan datang tepat waktu dan kebersihan sepatu kamu kami jamin.</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Gallery -->

    <!-- Testimoni -->
    <nav class="testimoni pt-5 mt-5" id="testimoni">
        <div class="container my-5 pb-4">
            <div class="row">
                <h3 class="sub-heading fw-bold mb-4">Testimoni</h3>
                <div class="col-md-8">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-5 image mb-2">
                                        <img src="{{ asset('assets/img/icons/testi-01.jpg') }}" class="d-inline rounded-3 w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial mt-auto">
                                        <p class="mb-2">“Sepatu kerja gue yang udah dekil banget jadi bersih lagi. Bener-bener puas sama hasilnya. Proses jemput-antar juga gampang banget, ga ribet sama sekali!”</p>
                                        <h6 class="fw-bold">Putra</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-md-5 image mb-2">
                                        <img src="{{ asset('assets/img/icons/testi-02.jpg') }}" class="d-inline rounded-3 w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial mt-auto">
                                        <p class="mb-2">“Sepatu gue sering kotor karena touring motor, jadi butuh tempat cuci yang oke. Udah beberapa kali cuci di Alju Shoes Clean, hasilnya selalu memuaskan! Layanan pickup & delivery-nya juga super praktis, jadi ga perlu repot.”</p>
                                        <h6 class="fw-bold">Fachri</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-md-5 image mb-2">
                                        <img src="{{ asset('assets/img/icons/testi-03.jpg') }}" class="d-inline rounded-3 w-100" alt="...">
                                    </div>
                                    <div class="col-md-7 testimonial mt-auto">
                                        <p class="mb-2">“Sebagai pelanggan setia, gue selalu puas dengan hasil cuci sepatu di Alju Shoes Clean. Sepatu sneakers gue jadi kelihatan baru lagi.”</p>
                                        <h6 class="fw-bold">Dita</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md text-end">
                    <div class="carousel-control my-carousel-control">
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <i class='bx bx-chevron-left text-my-primary'></i>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <i class='bx bx-chevron-right text-my-primary'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Testimoni -->

    <div class="modal fade" id="treatmentModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="treatmentModalLabel">Treatment</h2>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-my-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/page/home.js') }}"></script>
@endsection