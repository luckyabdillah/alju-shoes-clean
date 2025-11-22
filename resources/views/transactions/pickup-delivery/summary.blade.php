@extends('layouts.main')

@section('content')
    
    <nav class="summary">
        <div class="container mt-4 mb-5">
            
            <div class="row">
                <div class="col">
                    <h3 class="fw-bold">Ringkasan Pemesanan</h3>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="/#" class="my-link">Beranda</a></li>
                          <li class="breadcrumb-item"><a href="/pickup-delivery/details" class="my-link">Order</a></li>
                          <li class="breadcrumb-item active fw-bold" aria-current="page">Ringkasan Pemesanan</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <hr>

            <div class="row justify-content-around mt-5">
                <div class="col-md-7">
                    <h5 class="fw-bold mb-3 text-my-primary">Data Pemesan</h5>
                    <div class="mb-3">
                        <hr>
                        <h6 class="fw-bold">{{ $identity['name'] }} &nbsp;<span class="fw-normal">(0{{ $identity['whatsapp_number'] }})</span></h6>
                    </div>
                    <hr>
                    <h5 class="fw-bold mt-5 mb-3 text-my-primary">Rincian Pesanan</h5>
                    <hr>
                    <table border="0" cellpadding="0" style="width: 100%" class="summary-item-desktop">
                        <tr class="fw-bold">
                            <td class="pb-2" style="width: 13%">Item</td>
                            <td class="pb-2" style="width: 40%"></td>
                            <td class="pb-2">Treatment</td>
                        </tr>
                        @foreach ($transactions['transaction_details'] as $item)
                            <tr>
                                <td class="fw-bold text-secondary pt-3">{{ ucfirst($item['type']) }}</td>
                                <td class="fw-semibold pt-3">{{ $item['merk'] }}&nbsp; <span style="border-right: {{ $item['size'] ? '1px' : '0' }} solid rgb(196, 196, 196)"></span> &nbsp; <span class="text-secondary fw-semibold">{{ $item['size'] ? 'Size ' : '' }}</span>{{ $item['size'] ?? '' }}</td>
                                <td class="pt-3">{{ $item['treatment_name'] }}</td>
                            </tr>
                            <tr class="detail-item-summary">
                                <td class="pb-3 pe-2"></td>
                                <td class="text-secondary pb-3 pe-2">({{ $item['description'] }})</td>
                                <td class="text-secondary pb-3 pe-2" style="vertical-align: top">Rp {{ number_format($item['cost'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="summary-item-mobile">
                        @foreach ($transactions['transaction_details'] as $item)
                            <div class="detail-item-summary py-3">
                                <h6 class="fw-bold">Item {{ $loop->iteration }}</h6>
                                <small class="text-secondary fw-bold d-block mb-1">{{ ucfirst($item['type']) }}</small>
                                <p class="mb-2">{{ $item['merk'] }}&nbsp; <span style="border-right: {{ $item['size'] ? '1px' : '0' }} solid rgb(196, 196, 196)"></span> &nbsp; <span class="text-secondary fw-semibold">{{ $item['size'] ? 'Size ' : '' }}</span>{{ $item['size'] ?? '' }}</p>
                                <p class="mb-2 text-secondary">Desc: {{ $item['description'] }}</p>
                                <small class="text-secondary fw-bold d-block mb-1">Treatment</small>
                                <div class="row">
                                    <div class="col">
                                        <h6>{{ $item['treatment_name'] }}</h6>
                                    </div>
                                    <div class="col-4 text-end text-secondary">
                                        <span>Rp {{ number_format($item['cost'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mt-0">Tanggal Mulai</h6>
                                <span class="fw-semibold">{{ date('d M Y, H:i:s', strtotime($transactions['transaction_start'])) }}</span>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mt-0">Tanggal Selesai</h6>
                                <span class="fw-semibold">{{ date('d M Y, H:i:s', strtotime($transactions['transaction_end'])) }}</span>
                            </div>
                        </div>
                    </div>
                    @if ($transactions['isAbove3PM'] == true)
                        <div class="text-danger mt-3">
                            <span>Mohon maaf, waktu selesai kami perpanjang 1 hari karena kakak taruh sepatu lewat dari jam 3 sore</span>
                        </div>
                    @endif
                    @if ($transactions['skipHoliday'] == true)
                        <div class="text-danger mt-3">
                            <span>Mohon maaf, waktu selesai kami perpanjang 1 hari karena toko tutup di hari {{ $transactions['holidayInDay'] }}</span>
                        </div>
                    @endif
                    <h5 class="fw-bold mt-5 mb-3 text-my-primary">Kode Promo</h5>
                    <form id="promoCodeForm">
                        <div class="input-group">
                            <input type="text" name="promo_code_input" id="promo_code_input" class="form-control" placeholder="Kode Promo (Jika Ada)" style="text-transform: uppercase" required>
                            <button class="btn btn-my-primary btn-submit-promo" style="min-width: 80px;">Submit</button>
                        </div>
                    </form>
                    <h5 class="fw-bold mt-5 mb-3 text-my-primary">Pembayaran</h5>
                    <hr>
                    <label class="text-secondary fw-semibold">Total Bayar ({{ $transactions['total_item'] }} item) &nbsp;<span class="fw-bold text-dark">Rp <span class="total">{{ number_format($transactions['total_cost'], 0, ',', '.') }}</span></span></label>
                    <hr>
                    <form action="/pickup-delivery/summary" method="post" enctype="multipart/form-data" id="summarySubmitForm">
                        @csrf
                        <div class="mt-3">
                            <input type="hidden" name="promo_code">
                            <div class="qris my-4 pt-3">
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-md-5 qris-image">
                                            <img src="{{ asset('assets/img/icons/qris-alju-new.jpg') }}" alt="QRIS Alju Shoes Clean" class="img-thumbnail img-fluid mb-3">
                                            <a download="QRIS Alju Shoes Clean.jpg" href="{{ asset('assets/img/icons/qris-alju-new.jpg') }}" class="d-block btn btn-outline-my-secondary mb-2">Download Gambar</a>
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#hintModal" class="btn btn-outline-my-secondary btn-payment-instructions">Petunjuk Pembayaran</a>
                                        </div>
                                        <div class="col payment-instructions">
                                            <h6 class="fw-bold mb-3">Petunjuk Pembayaran</h6>
                                            <ol>
                                                <li class="mb-2">Buka aplikasi pembayaran</li>
                                                <li class="mb-2">Pilih QRIS sebagai metode pembayaran</li>
                                                <li class="mb-2">Scan kode QRIS</li>
                                                <li class="mb-2">Masukkan nominal transaksi</li>
                                                <li class="mb-2">Verifikasi transaksi</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="proof_of_payment" class="form-label">Upload Bukti Pembayaran</label>
                                    <input type="file" accept="image/*" name="proof_of_payment" id="proof_of_payment" class="form-control @error('proof_of_payment') is-invalid @enderror" @error('proof_of_payment') autofocus @enderror required>
                                    <div class="mt-1">
                                        <i class="bx bxs-info-circle bx-xs"></i> <small>Maks. ukuran file 2 MB</small>
                                    </div>
                                    @error('proof_of_payment')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 submit-desktop">
                            <div class="col">
                                <a href="/pickup-delivery/details" class="btn btn-outline-my-primary py-2 w-100 btn-back">Sebelumnya</a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit">Proses Order</button>
                            </div>
                        </div>
                    </form>
                </div>
                <input type="hidden" name="subtotal" value="{{ $transactions['cost'] }}">
                <input type="hidden" name="shipping_cost" value="{{ $transactions['shipping_cost'] }}">
                <div class="col-md-4">
                    <div class="order-summary-desktop position-sticky">
                        <h5 class="fw-bold">Ringkasan Pemesanan</h5>
                        <h6 class="mt-3">Total Item &nbsp;<span class="fw-bold total-item">{{ $transactions['total_item'] }}</span></h6>
                        <hr>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp<span class="subtotal">{{ number_format($transactions['cost'], 0, ',', '.') }}</span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-0">Delivery/Shipping</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp{{ number_format($transactions['shipping_cost'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2 text-my-primary discount-field">
                            <div class="col-6">
                                <h6 class="mb-0 discount-text">Diskon</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp<span class="discount-amount">0</span></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <h6>Total</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok fw-bold">Rp<span class="total">{{ number_format($transactions['total_cost'], 0, ',', '.') }}</span></span>
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
                    <h6>Total Item &nbsp;<span class="fw-bold total-item">{{ $transactions['total_item'] }}</span></h6>
                    <hr>
                    <div class="row mt-3 mb-2">
                        <div class="col-6">
                            <h6>Subtotal</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp<span class="subtotal">{{ number_format($transactions['cost'], 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mb-0">Delivery/Shipping</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp{{ number_format($transactions['shipping_cost'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="row mt-2 text-my-primary discount-field">
                        <div class="col-6">
                            <h6 class="mb-0 discount-text">Diskon</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp<span class="discount-amount">0</span></span>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>Total</h6>
                    </div>
                    <div class="col-6 text-end">
                        <span class="d-inline-blcok fw-bold total">Rp<span class="total">{{ number_format($transactions['total_cost'], 0, ',', '.') }}</span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="/pickup-delivery/details" class="btn btn-outline-my-primary py-2 w-100 btn-back-mobile">Sebelumnya</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit-mobile">Proses Order</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="hintModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2 class="modal-title fs-5 fw-bold" id="hintModalLabel">Petunjuk Pembayaran</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: unset"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold mb-3">Bayar Melalui QRIS</h6>
                    <ol>
                        <li class="mb-2">Buka aplikasi pembayaran</li>
                        <li class="mb-2">Pilih QRIS sebagai metode pembayaran</li>
                        <li class="mb-2">Scan kode QRIS</li>
                        <li class="mb-2">Masukkan nominal transaksi</li>
                        <li class="mb-2">Verifikasi transaksi</li>
                    </ol>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .summary-mobile.show {
            height: 180px;
            animation: expand forwards;
            animation-delay: 0.1s;
        }
    </style>
@endpush

@section('scripts')
    <script>
        async function checkPromoCode(code) {
            try {
                const response = await fetch(`/check-promo-code/${code}`)
                if (!response.ok) {
                    throw new Error(response.status || 'Failed to fetch data')
                }

                const jsonData = await response.json()
                if (!jsonData.data || jsonData.data.length === 0) {
                    throw new Error('No data available')
                }

                return jsonData.data
            } catch (error) {
                console.error(error)
                throw error
            }
        }

        document.querySelector('#promoCodeForm').addEventListener('submit', async function (e) {
            e.preventDefault()
            const submitButton = document.querySelector('.btn-submit-promo')
            const promoCode = document.querySelector('[name="promo_code_input"]').value
            const subtotal = parseInt(document.querySelector('[name="subtotal"]').value, 10)
            const shippingCost = parseInt(document.querySelector('[name="shipping_cost"]').value, 10)

            document.querySelector('[name="promo_code"]').value = ''

            submitButton.innerHTML = `
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `
                        
            document.querySelectorAll('.discount-text').forEach(discount => {
                discount.innerText = 'Diskon'
            })
            
            document.querySelectorAll('.discount-amount').forEach(discount => {
                discount.innerText = 0
            })
            
            document.querySelectorAll('.total').forEach(el => {
                el.innerText = (subtotal + shippingCost).toLocaleString('id-ID')
            })

            try {
                const promo = await checkPromoCode(promoCode)

                submitButton.innerHTML = 'Submit'

                if ((subtotal + shippingCost) >= promo.min_spend) {
                    if (promo.promo_type == 'treatment') {
                        discountAmount = promo.type == 'amount' ? promo.amount : subtotal * promo.amount / 100
                        totalAfterDiscount = (subtotal - discountAmount) + shippingCost
                        
                        document.querySelectorAll('.discount-text').forEach(discount => {
                            discount.innerText = 'Diskon Treatment'
                        })
                        
                        document.querySelectorAll('.discount-amount').forEach(discount => {
                            discount.innerText = discountAmount.toLocaleString('id-ID')
                        })
                        
                        document.querySelectorAll('.total').forEach(subtotal => {
                            subtotal.innerText = totalAfterDiscount.toLocaleString('id-ID')
                        })

                        document.querySelector('[name="promo_code"]').value = promo.code

                        return Swal({
                            title: 'Sukses',
                            text: 'Kode berhasil dipasang',
                            type: 'success'
                        })
                    } else {
                        discountAmount = promo.type == 'amount' ? promo.amount : shippingCost * promo.amount / 100
                        totalAfterDiscount = (shippingCost - discountAmount) + subtotal
                        
                        document.querySelectorAll('.discount-text').forEach(discount => {
                            discount.innerText = 'Diskon Delivery'
                        })
                        
                        document.querySelectorAll('.discount-amount').forEach(discount => {
                            discount.innerText = discountAmount.toLocaleString('id-ID')
                        })
                        
                        document.querySelectorAll('.total').forEach(subtotal => {
                            subtotal.innerText = totalAfterDiscount.toLocaleString('id-ID')
                        })

                        document.querySelector('[name="promo_code"]').value = promo.code

                        return Swal({
                            title: 'Sukses',
                            text: 'Kode berhasil dipasang',
                            type: 'success'
                        })
                    }
                } else {
                    return Swal({
                        title: 'Oops!',
                        text: `Min. belanja untuk menggunakan kode ini adalah Rp ${promo.min_spend.toLocaleString('id-ID')}`,
                        type: 'error'
                    })
                }
            } catch (error) {
                submitButton.innerHTML = 'Submit'
                console.error(error)
                Swal({
                    title: 'Oops!',
                    text: 'Kode promo tidak ada atau tidak valid',
                    type: 'error'
                })
            }
        })

        $('[name="payment_method"]').on('change', function(e) {
            const qris = document.querySelector('.qris');
            const uploadImageField = $('input[name="proof_of_payment"]');
            if (this.value == 'qris') {
                qris.classList.remove('d-none');
                uploadImageField.prop('required', true);
            } else {
                qris.classList.add('d-none');
                uploadImageField.prop('required', false);
            }
        });

        document.querySelector('#summarySubmitForm').addEventListener('submit', function(e) {
            const submitButton = document.querySelector('.btn-submit');
            const backButton = document.querySelector('.btn-back');
            submitButton.setAttribute('disabled', true);
            backButton.classList.add('disabled');
            submitButton.innerHTML = `
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            e.submit();
        });

        document.querySelector('.btn-submit-mobile').addEventListener('click', e => {
            const backButton = document.querySelector('.btn-back-mobile');
            const form = document.querySelector('#summarySubmitForm');

            if (form.checkValidity()) {
                e.target.setAttribute('disabled', true);
                e.target.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
                backButton.classList.add('disabled');
                
                form.submit();
            } else {
                form.reportValidity();
            }
        })

        document.querySelector('.btn-expand').addEventListener('click', e => {
            e.preventDefault()
            const summaryMobile = document.querySelector('.summary-mobile')
            e.target.classList.toggle('bx-rotate-180')
            summaryMobile.classList.toggle('show')
        })
    </script>
@endsection