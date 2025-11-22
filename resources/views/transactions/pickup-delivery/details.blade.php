@extends('layouts.main')

@section('content')
    <nav class="order">
        <div class="container mt-4 mb-5">
            @if (session()->has('failed'))
                <div class="flash-data-failed" data-flash="{{ session('failed') }}"></div>
            @endif
            <div class="row">
                <div class="col">
                    <h3 class="fw-bold">Pickup-Delivery Order</h3>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="/" class="my-link">Beranda</a></li>
                          <li class="breadcrumb-item active fw-bold" aria-current="page">Pickup-Delivery Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <hr>
            <div class="row mt-5 justify-content-around">
                <div class="col-md-7">
                    <div class="row detail-order">
                        <div class="col">
                            <h5 class="fw-bold">Identitas Diri</h5>
                        </div>
                        <div class="col text-end form-step">
                            <button class="border-0 step-1 active"></button>
                            <button class="border-0 step-2 active"></button>
                            <span>2<span class="text-secondary">/2</span></span>
                        </div>
                    </div>
                    <input type="hidden" name="holiday_in_number" value="{{ $holidayInNumber }}">
                    <input type="hidden" name="holiday_in_day" value="{{ $holidayInDay }}">
                    <div class="row mt-4">
                        <div class="col fw-semibold">
                            <form action="/pickup-delivery/details" method="post" id="detailOrderForm">
                                @csrf
                                <div class="item-container">
                                    <div class="identity mb-4">
                                        <div class="mb-3">
                                            <label for="whatsapp_number" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input class="form-control @error('whatsapp_number') is-invalid @enderror" type="text" id="whatsapp_number" name="whatsapp_number" placeholder="8123456xxxx" autocomplete="off" value="{{ old('whatsapp_number', ($identity['whatsapp_number'] ?? '' )) }}" required>
                                                <a href="javascript:void(0)" class="btn btn-my-primary btn-check-number" style="min-width: 80px;">Check</a>
                                                @error('whatsapp_number')
                                                    <div class="invalid-feedback text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pemesan <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama pemesan" required autocomplete="off" value="{{ old('name', ($identity['name'] ?? '' )) }}">
                                            @error('name')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email pemesan" required autocomplete="off" value="{{ old('email', ($identity['email'] ?? '' )) }}">
                                            @error('email')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat Penjemputan <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Alamat">{{ old('address', ($identity['address']) ?? '' ) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="benchmark" class="form-label">Detail Lainnya <span class="text-danger">*</span></label>
                                            <input type="text" name="benchmark" id="benchmark" class="form-control @error('benchmark') is-invalid @enderror" placeholder="Cth: Blok / Unit No., Patokan" required autocomplete="off" value="{{ old('benchmark', ($identity['benchmark'] ?? '' )) }}">
                                            @error('benchmark')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="pickup_date" class="form-label">Tanggal Pickup ({{ $holidayInDay }} Libur) <span class="text-danger">*</span></label>
                                                <input type="date" name="pickup_date" id="pickup_date" class="form-control @error('pickup_date') is-invalid @enderror" required autocomplete="off" value="{{ old('pickup_date', ($identity['pickup_date'] ?? '' )) }}">
                                                @error('pickup_date')
                                                    <div class="invalid-feedback text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col mb-3">
                                                <label for="pickup_time" class="form-label">Jam Pickup (10:00 - 17:00) <span class="text-danger">*</span></label>
                                                <input type="time" name="pickup_time" id="pickup_time" min="10:00" max="17:00" class="form-control @error('pickup_time') is-invalid @enderror" required autocomplete="off" value="{{ old('pickup_time', ($identity['pickup_time'] ?? '' )) }}">
                                                @error('pickup_time')
                                                    <div class="invalid-feedback text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col">
                                        <a href="/pickup-delivery" class="btn btn-outline-my-primary py-2 w-100 btn-back">Sebelumnya</a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-my-primary py-2 w-100 btn-order">Order Sekarang</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="order-summary-desktop position-sticky">
                        <h5 class="fw-bold">Ringkasan Pemesanan</h5>
                        <h6 class="mt-3">Total Item &nbsp;<span class="fw-bold total-item">{{ $total_items }}</span></h6>
                        <hr>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok subtotal">Rp{{ number_format($treatment_cost, 0, '.', ',') }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-0">Delivery/Shipping</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp0</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <h6>Total</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok fw-bold total">Rp{{ number_format($treatment_cost, 0, '.', ',') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center my-5 pb-2 mobile-footer" style="color: #667085">
                        &copy; 2024 aljushoe <span class="px-1">•</span> All Right Reserved
                    </div>
                </div>
            </div>
            <div class="order-summary-mobile fixed-bottom">
                <div class="text-center pt-2 mb-1">
                    <a href="javascript:void(0)" class="d-block text-dark border-0 bg-transparent btn-expand">
                        <i class="bx bx-chevron-up bx-sm"></i>
                    </a>
                </div>
                <div class="summary-mobile">
                    <h6>Total Item &nbsp;<span class="fw-bold total-item">{{ $total_items }}</span></h6>
                    <hr>
                    <div class="row mt-3 mb-2">
                        <div class="col-6">
                            <h6>Subtotal</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok subtotal">Rp{{ number_format($treatment_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mb-0">Delivery/Shipping</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp0</span>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>Total</h6>
                    </div>
                    <div class="col-6 text-end">
                        <span class="d-inline-blcok fw-bold total">Rp{{ number_format($treatment_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="/pickup-delivery" class="btn btn-outline-my-primary py-2 w-100 btn-back-mobile">Sebelumnya</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit-mobile">Order Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/page/details-pd.js') }}"></script>
@endsection