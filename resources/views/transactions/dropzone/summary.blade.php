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
                          <li class="breadcrumb-item"><a href="/order/details" class="my-link">Order</a></li>
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
                                <td class="text-secondary pb-3 pe-2" style="vertical-align: top">Rp{{ number_format($item['cost'], 0, ',', '.') }}</td>
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
                                        <span>Rp{{ number_format($item['cost'], 0, ',', '.') }}</span>
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
                    <h5 class="fw-bold mt-5 mb-2 text-my-primary">Kode Promo</h5>
                    <p class="text-muted">Gunakan sampai 2 kupon: treatment + pickup-delivery (jika ada)</p>
                    <form id="promoCodeForm">
                        <div class="input-group">
                            <input type="text" name="promo_code_input" id="promo_code_input" class="form-control text-uppercase" placeholder="Kode Promo" required>
                            <button class="btn btn-my-primary btn-submit-promo" style="min-width: 80px;">Submit</button>
                        </div>
                    </form>
                    <div id="promo-container" class="d-flex flex-column">
                        <!-- Promo badges will be injected here when applied -->
                        <div id="treatment-promo" class="pt-3"></div>
                        <div id="delivery-promo" class="pt-2"></div>
                    </div>
                    <h5 class="fw-bold mt-5 mb-3 text-my-primary">Pembayaran</h5>
                    <hr>
                    <label class="text-secondary fw-semibold">Total Bayar ({{ $transactions['total_item'] }} item) &nbsp;<span class="fw-bold text-dark">Rp<span class="total">{{ number_format($transactions['total_cost'], 0, ',', '.') }}</span></span></label>
                    <hr>
                    <form action="/order/summary" method="post" enctype="multipart/form-data" id="summarySubmitForm">
                        @csrf
                        <div class="mt-3">
                            <input type="hidden" name="promo_code_treatment" value="{{ old('promo_code_treatment', '') }}">
                            <input type="hidden" name="promo_code_delivery" value="{{ old('promo_code_delivery', '') }}">
                            <!-- legacy single promo field (kept for backward compatibility) -->
                            <input type="hidden" name="promo_code" value="{{ old('promo_code', '') }}">
                            @if ($transactions['transaction_type'] == 'dropzone')
                                <h6 class="fw-bold mb-3">Bayar Dengan</h6>
                                <div class="mt-2 mb-5">
                                    <div class="row">
                                        <div class="col">
                                            <label class="payment-option">
                                                <input type="radio" name="payment_method" value="qris" required {{ old('payment_method') == 'qris' ? 'checked' : '' }}>
                                                QRIS
                                            </label>
                                        </div>
                                        <div class="col">
                                            <label class="payment-option">
                                                <input type="radio" name="payment_method" value="cash" required {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                                Cash/Tunai
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="qris {{ old('payment_method') == 'qris' ? '' : 'd-none' }} mb-4">
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
                                        <input type="file" accept="image/*" name="proof_of_payment" id="proof_of_payment" class="form-control @error('proof_of_payment') is-invalid @enderror" @error('proof_of_payment') required autofocus @enderror>
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
                            @else
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
                            @endif
                        </div>
                        <div class="row mt-5 submit-desktop">
                            <div class="col">
                                <a href="/order/details" class="btn btn-outline-my-primary py-2 w-100 btn-back">Sebelumnya</a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit">Proses Order</button>
                            </div>
                        </div>
                    </form>
                </div>
                <input type="hidden" name="transaction_type" value="{{ $transactions['transaction_type'] }}">
                <input type="hidden" name="subtotal" value="{{ $transactions['cost'] }}">
                <input type="hidden" name="shipping_cost" value="{{ $transactions['shipping_cost'] }}">
                <div class="col-md-4">
                    <div class="order-summary-desktop position-sticky">
                        <div id="summary-loader" class="summary-loader" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
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
                                <h6 class="mb-0 discount-text">Diskon Treatment</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp<span id="treatment-discount-amount" class="discount-amount treatment-discount-amount">0</span></span>
                            </div>
                        </div>
                        <div class="row mt-2 text-my-primary discount-field">
                            <div class="col-6">
                                <h6 class="mb-0 discount-text">Diskon Delivery</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok">Rp<span class="discount-amount delivery-discount-amount">0</span></span>
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
                        &copy; {{ date('Y') }} aljushoesclean <span class="px-1">•</span> All Right Reserved
                    </div>
                </div>
            </div>
            <div class="order-summary-mobile fixed-bottom">
                <div id="summary-loader-mobile" class="summary-loader-mobile" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
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
                    {{-- <div class="row mt-2 text-my-primary discount-field">
                        <div class="col-6">
                            <h6 class="mb-0 discount-text">Diskon</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp<span class="discount-amount">0</span></span>
                        </div>
                    </div> --}}
                    <div class="row mt-2 text-my-primary discount-field">
                        <div class="col-6">
                            <h6 class="mb-0 discount-text">Diskon Treatment</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp<span class="discount-amount treatment-discount-amount">0</span></span>
                        </div>
                    </div>
                    <div class="row mt-2 text-my-primary discount-field">
                        <div class="col-6">
                            <h6 class="mb-0 discount-text">Diskon Delivery</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok">Rp<span class="discount-amount delivery-discount-amount">0</span></span>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>Total</h6>
                    </div>
                    <div class="col-6 text-end">
                        <span id="mobile-total" class="d-inline-blcok fw-bold">Rp<span class="total">{{ number_format($transactions['total_cost'], 0, ',', '.') }}</span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="/order/details" class="btn btn-outline-my-primary py-2 w-100 btn-back-mobile">Sebelumnya</a>
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
            height: 210px;
            animation: expand forwards;
            animation-delay: 0.1s;
        }
        .summary-loader { position: absolute; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(255,255,255,0.8); z-index: 50; }
        .summary-loader-mobile { position: absolute; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(255,255,255,0.85); z-index: 60; }
        /* skeleton placeholder */
        /* .skeleton {
            display: inline-block;
            background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.2s linear infinite;
            border-radius: 6px;
        }
        .total-skeleton { width: 120px; height: 18px; }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0 }
            100% { background-position: -200% 0 }
        } */
    </style>
@endpush

@section('scripts')
    <script>
        // --- Promo helper & state ---
        async function checkPromoCode(code) {
            try {
                const response = await fetch(`/check-promo-code/${code}`)
                if (!response.ok) {
                    throw new Error(response.status || 'Failed to fetch data')
                }

                const jsonData = await response.json()
                if (!jsonData.data || (Array.isArray(jsonData.data) && jsonData.data.length === 0)) {
                    throw new Error('No data available')
                }

                return jsonData.data
            } catch (error) {
                console.error(error)
                throw error
            }
        }

        let appliedTreatmentPromo = null
        let appliedDeliveryPromo = null

        function formatRupiah(value) {
            return (value || 0).toLocaleString('id-ID')
        }

        // function setMobileTotalSkeleton(show) {
        //     const mobileTotal = document.getElementById('mobile-total')
        //     if (!mobileTotal) return
        //     if (show) {
        //         // store previous content
        //         if (!mobileTotal.dataset.prev) mobileTotal.dataset.prev = mobileTotal.innerHTML
        //         mobileTotal.innerHTML = 'Rp<span class="skeleton total-skeleton"></span>'
        //     } else {
        //         // restore previous content if available
        //         if (mobileTotal.dataset.prev) {
        //             mobileTotal.innerHTML = mobileTotal.dataset.prev
        //             delete mobileTotal.dataset.prev
        //         }
        //     }
        // }

        function showSummaryLoader(show, target = 'desktop') {
            // target: 'desktop' | 'mobile' | 'both'
            const loader = document.getElementById('summary-loader')
            const mobileLoader = document.getElementById('summary-loader-mobile')

            if ((target === 'desktop' || target === 'both') && loader) loader.style.display = show ? 'flex' : 'none'
            if ((target === 'mobile' || target === 'both') && mobileLoader) mobileLoader.style.display = show ? 'flex' : 'none'

            // if ((target === 'mobile' || target === 'both')) setMobileTotalSkeleton(show)

            // when hiding loaders, update the summary so numbers refresh after skeleton removed
            if (!show) {
                // small timeout so UI updates feel smoother
                setTimeout(() => updateDesktopSummary(), 80)
            }
        }

        function renderPromoBadge(type, code) {
            const container = document.getElementById(type + '-promo')
            if (!container) return
            if (!code) {
                container.innerHTML = ''
                return
            }

            const label = type === 'treatment' ? 'Treatment' : 'Delivery'
            container.innerHTML = `
                <span class="badge border border-success text-success rounded-pill py-2 px-3">
                    ${code} (${label})
                    <i class="bx bx-x bx-sm remove-promo" data-type="${type}" style="cursor: pointer"></i>
                </span>
            `
        }

        function updateDesktopSummary() {
            const subtotal = parseInt(document.querySelector('[name="subtotal"]').value, 10)
            const shippingCost = parseInt(document.querySelector('[name="shipping_cost"]').value, 10)

            const treatmentDiscount = appliedTreatmentPromo ? appliedTreatmentPromo.amount : 0
            const deliveryDiscount = appliedDeliveryPromo ? appliedDeliveryPromo.amount : 0

            const total = (subtotal - treatmentDiscount) + (shippingCost - deliveryDiscount)

            const treatmentEl = document.querySelectorAll('.treatment-discount-amount').forEach(el => el.innerText = formatRupiah(treatmentDiscount));
            const deliveryEl = document.querySelectorAll('.delivery-discount-amount').forEach(el => el.innerText = formatRupiah(deliveryDiscount));

            document.querySelectorAll('.total').forEach(el => {
                el.innerText = formatRupiah(total)
            })

            // hidden inputs for server submission
            const promoTreatInput = document.querySelector('[name="promo_code_treatment"]')
            const promoDelInput = document.querySelector('[name="promo_code_delivery"]')
            if (promoTreatInput) promoTreatInput.value = appliedTreatmentPromo ? appliedTreatmentPromo.code : ''
            if (promoDelInput) promoDelInput.value = appliedDeliveryPromo ? appliedDeliveryPromo.code : ''
            const legacyInput = document.querySelector('[name="promo_code"]')
            if (legacyInput) {
                const parts = []
                if (appliedTreatmentPromo) parts.push(appliedTreatmentPromo.code)
                if (appliedDeliveryPromo) parts.push(appliedDeliveryPromo.code)
                legacyInput.value = parts.join(',')
            }

            // render badges
            renderPromoBadge('treatment', appliedTreatmentPromo ? appliedTreatmentPromo.code : null)
            renderPromoBadge('delivery', appliedDeliveryPromo ? appliedDeliveryPromo.code : null)
        }

        // Hydrate applied promos from server-side old() values after validation errors
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const subtotal = parseInt(document.querySelector('[name="subtotal"]').value, 10)
                const shippingCost = parseInt(document.querySelector('[name="shipping_cost"]').value, 10)
                const transactionType = document.querySelector('[name="transaction_type"]').value

                let promoTreatVal = document.querySelector('[name="promo_code_treatment"]')?.value?.trim() || ''
                let promoDelVal = document.querySelector('[name="promo_code_delivery"]')?.value?.trim() || ''
                const legacyVal = document.querySelector('[name="promo_code"]')?.value?.trim() || ''

                // if new fields empty but legacy has CSV, try to assign them
                if (!promoTreatVal && !promoDelVal && legacyVal) {
                    const parts = legacyVal.split(',').map(p => p.trim()).filter(p => p)
                    promoTreatVal = parts[0] || ''
                    promoDelVal = parts[1] || ''
                }

                // fetch and hydrate treatment promo
                if (promoTreatVal) {
                    try {
                        const res = await checkPromoCode(promoTreatVal.toUpperCase())
                        const promo = Array.isArray(res) ? res[0] : res
                        if (promo && promo.promo_type === 'treatment') {
                            const discountAmount = promo.type === 'amount' ? promo.amount : Math.floor(subtotal * promo.amount / 100)
                            appliedTreatmentPromo = { code: promo.code, amount: discountAmount, raw: promo }
                        }
                    } catch (e) {
                        // ignore hydration errors
                        console.warn('Failed to hydrate treatment promo', e)
                    }
                }

                // fetch and hydrate delivery promo
                if (promoDelVal) {
                    try {
                        const res = await checkPromoCode(promoDelVal.toUpperCase())
                        const promo = Array.isArray(res) ? res[0] : res
                        if (promo && promo.promo_type === 'delivery') {
                            if (transactionType !== 'dropzone') {
                                const discountAmount = promo.type === 'amount' ? promo.amount : Math.floor(shippingCost * promo.amount / 100)
                                appliedDeliveryPromo = { code: promo.code, amount: discountAmount, raw: promo }
                            }
                        }
                    } catch (e) {
                        console.warn('Failed to hydrate delivery promo', e)
                    }
                }

                // update UI
                updateDesktopSummary()
            } catch (err) {
                console.error('Promo hydration error', err)
            }
        })

        // Apply promo (support two types)
        document.querySelector('#promoCodeForm').addEventListener('submit', async function (e) {
            e.preventDefault()
            const submitButton = document.querySelector('.btn-submit-promo')
            const promoCodeRaw = document.querySelector('[name="promo_code_input"]').value || ''
            const promoCode = promoCodeRaw.trim().toUpperCase()
            const transactionType = document.querySelector('[name="transaction_type"]').value
            const subtotal = parseInt(document.querySelector('[name="subtotal"]').value, 10)
            const shippingCost = parseInt(document.querySelector('[name="shipping_cost"]').value, 10)

            submitButton.disabled = true
            submitButton.innerHTML = `
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`

            // show overlays on both desktop and mobile
            showSummaryLoader(true, 'both')

            try {
                const result = await checkPromoCode(promoCode)
                const promo = Array.isArray(result) ? result[0] : result
                if (!promo) throw new Error('not_found')

                if ((subtotal + shippingCost) < promo.min_spend) {
                    throw { type: 'min_spend', min: promo.min_spend }
                }

                if (promo.promo_type === 'treatment') {
                    const discountAmount = promo.type === 'amount' ? promo.amount : Math.floor(subtotal * promo.amount / 100)
                    appliedTreatmentPromo = { code: promo.code, amount: discountAmount, raw: promo }
                } else if (promo.promo_type === 'delivery') {
                    if (transactionType === 'dropzone') {
                        throw { type: 'invalid_dropzone' }
                    }
                    const discountAmount = promo.type === 'amount' ? promo.amount : Math.floor(shippingCost * promo.amount / 100)
                    appliedDeliveryPromo = { code: promo.code, amount: discountAmount, raw: promo }
                } else {
                    throw new Error('invalid_type')
                }

                // don't update immediately — wait until loader hides so mobile skeleton shows
                // update will happen after we hide the loaders
            } catch (err) {
                console.error(err)
                if (err && err.type === 'min_spend') {
                    Swal({ title: 'Oops!', text: `Min. belanja untuk menggunakan kode ini adalah Rp${err.min.toLocaleString('id-ID')}`, type: 'error' })
                } else if (err && err.type === 'invalid_dropzone') {
                    Swal({ title: 'Oops!', text: 'Kode yang kamu masukkan hanya berlaku untuk layanan Pickup & Delivery', type: 'error' })
                } else {
                    Swal({ title: 'Oops!', text: 'Kode promo tidak valid', type: 'error' })
                }
            } finally {
                document.querySelector('[name="promo_code_input"]').value = ''
                submitButton.disabled = false
                submitButton.innerHTML = 'Submit'
                showSummaryLoader(false, 'both')
            }
        })

        // allow removing applied promo by clicking the x (show loader briefly)
        document.getElementById('promo-container').addEventListener('click', function (e) {
            const rem = e.target.closest('.remove-promo')
            if (!rem) return
            const type = rem.dataset.type

            // show loaders on both desktop and mobile while removing
            showSummaryLoader(true, 'both')

            // simulate a short async removal so spinner is visible
            setTimeout(() => {
                if (type === 'treatment') appliedTreatmentPromo = null
                if (type === 'delivery') appliedDeliveryPromo = null
                // hide loaders — updateDesktopSummary will run after hide
                showSummaryLoader(false, 'both')
            }, 350)
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