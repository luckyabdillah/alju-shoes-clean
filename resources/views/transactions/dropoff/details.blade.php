@extends('layouts.main')

@section('content')
    
    <nav class="order">
        <div class="container mt-4 mb-5">
            @if (session()->has('failed'))
                <div class="flash-data-failed" data-flash="{{ session('failed') }}"></div>
            @endif
            {{-- @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
            @endif --}}
            <div class="row">
                <div class="col">
                    <h3 class="fw-bold">Order Treatment</h3>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#" class="my-link">Beranda</a></li>
                          <li class="breadcrumb-item active fw-bold" aria-current="page">Order Treatment</li>
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
                    <div class="row mt-4">
                        <div class="col fw-semibold">
                            <form action="/order/details" method="post">
                                @csrf
                                <div class="item-container">
                                    <div class="identity mb-4">
                                        <div class="mb-3">
                                            <label for="whatsapp_number" class="form-label">Nomor Telepon (WhatsApp)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input class="form-control @error('whatsapp_number') is-invalid @enderror" type="text" id="whatsapp_number" name="whatsapp_number" placeholder="8123456xxxx" autocomplete="off" value="{{ old('whatsapp_number', ($identity['whatsapp_number'] ?? '' )) }}" required>
                                                @error('whatsapp_number')
                                                    <div class="invalid-feedback text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <a href="javascript:void(0)" class="btn btn-my-primary btn-check-number" style="min-width: 80px;">Check</a>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pemesan</label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama pemesan" required autocomplete="off" value="{{ old('name', ($identity['name'] ?? '' )) }}">
                                            @error('name')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Alamat">{{ old('address', ($identity['address']) ?? '' ) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-5">
                                    <div class="col">
                                        <a href="/order?o={{ $outlet }}" class="btn btn-outline-my-primary py-2 w-100">Sebelumnya</a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-my-primary py-2 w-100">Order Sekarang</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-top" style="top: 80px">
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
                </div>
            </div>
        </div>
    </nav>

@endsection

@section('scripts')

    <script>
        
    </script>

    <script src="{{ asset('assets/js/order-script.js') }}"></script>
@endsection