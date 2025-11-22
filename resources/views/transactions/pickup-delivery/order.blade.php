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
                            <h5 class="fw-bold">Isi Detail Order</h5>
                        </div>
                        <div class="col text-end form-step">
                            <button class="border-0 step-1 active"></button>
                            <button class="border-0 step-2"></button>
                            <span>1<span class="text-secondary">/2</span></span>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col fw-semibold">
                            <form action="/pickup-delivery" method="post" id="orderForm">
                                @csrf
                                <div class="item-container">
                                    @if ($transactions)
                                        @foreach ($transactions['transaction_details'] as $item)
                                            <div class="detail-item item-{{ $loop->iteration }} mb-4">
                                                @if ($loop->index >= 1)
                                                    <hr>
                                                @endif
                                                <label class="form-label">Type Item</label>
                                                <div class="mb-3">
                                                    <select name="transaction_details[{{ $loop->iteration - 1 }}][type]" id="transaction_details[{{ $loop->iteration - 1 }}][type]" class="form-select type" required>
                                                        <option value="">Pilih Type Item</option>
                                                        <option value="sepatu" {{ $item['type'] == 'sepatu' ? 'selected' : '' }}>Sepatu</option>
                                                        <option value="tas" {{ $item['type'] == 'tas' ? 'selected' : '' }}>Tas</option>
                                                        <option value="sandal" {{ $item['type'] == 'sandal' ? 'selected' : '' }}>Sandal</option>
                                                        <option value="topi" {{ $item['type'] == 'topi' ? 'selected' : '' }}>Topi</option>
                                                    </select>
                                                    <div class="invalid-feedback text-start">Type wajib diisi</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="transaction_details[{{ $loop->iteration - 1 }}][treatment_details_id]" class="form-label">Treatment</label>
                                                    <div class="select-wrapper">
                                                        <select name="transaction_details[{{ $loop->iteration - 1 }}][treatment_details_id]" id="transaction_details[{{ $loop->iteration - 1 }}][treatment_details_id]" class="form-select treatment-select" required>
                                                            <option value="" data-price="0" class="select-type-first">Pilih Treatment</option>
                                                            @foreach ($treatments as $treatment)
                                                                <optgroup label="{{ $treatment->name }}" id="{{ $treatment->type }}">
                                                                    @foreach ($treatment->treatment_details as $detail)
                                                                        <option value="{{ $detail->id }}" data-price="{{ $detail->cost }}" {{ ($item['treatment_details_id'] == $detail->id) ? 'selected' : '' }}>{{ $detail->name }} ({{ $detail->processing_time }} hari kerja)</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="invalid-feedback text-start"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="transaction_details[{{ $loop->iteration - 1 }}][merk]" class="form-label">Merk Item</label>
                                                            <input class="form-control merk" type="text" id="transaction_details[{{ $loop->iteration - 1 }}][merk]" name="transaction_details[{{ $loop->iteration - 1 }}][merk]" placeholder="Cth: Nike" autocomplete="off" value="{{ $item['merk'] }}" required>
                                                            <div class="invalid-feedback text-start"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="transaction_details[{{ $loop->iteration - 1 }}][size]" class="form-label">Ukuran</label>
                                                        <input type="text" name="transaction_details[{{ $loop->iteration - 1 }}][size]" class="form-control size" autocomplete="off" id="transaction_details[{{ $loop->iteration - 1 }}][size]" placeholder="(Jika Ada)" value="{{ $item['size'] }}">
                                                        <div class="invalid-feedback text-start"></div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="transaction_details[{{ $loop->iteration - 1 }}][description]" class="form-label">Deskripsi Item</label>
                                                    <input class="form-control description" type="text" id="transaction_details[{{ $loop->iteration - 1 }}][description]" name="transaction_details[{{ $loop->iteration - 1 }}][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" value="{{ $item['description'] }}" required>
                                                    <div class="invalid-feedback text-start"></div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col">
                                                        <button type="button" class="btn btn-transparent text-danger btn-remove d-none" id="btn-remove-1"><i class="bx bx-trash fs-4"></i></button>
                                                        <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                                                    </div>
                                                    <div class="col text-end">
                                                        <span class="item-price" id="transaction_details[{{ $loop->iteration - 1 }}][price]">Rp{{ number_format($item['cost'], 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="detail-item item-1 mb-4">
                                            <label class="form-label">Type Item <span class="text-danger">*</span></label>
                                            <div class="mb-3">
                                                <select name="transaction_details[0][type]" id="transaction_details[0][type]" class="form-select type" required>
                                                    <option value="">Pilih Type Item</option>
                                                    <option value="sepatu">Sepatu</option>
                                                    <option value="tas">Tas</option>
                                                    <option value="sandal">Sandal</option>
                                                    <option value="topi">Topi</option>
                                                </select>
                                                <div class="invalid-feedback text-start">Type wajib diisi</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="transaction_details[0][treatment_details_id]" class="form-label d-block">Treatment <span class="text-danger">*</span></label>
                                                <div class="select-wrapper">
                                                    <select name="transaction_details[0][treatment_details_id]" id="transaction_details[0][treatment_details_id]" class="form-select treatment-select" required disabled>
                                                        <option value="" data-price="0" class="select-type-first">Pilih Type Terlebih Dahulu</option>
                                                        @foreach ($treatments as $treatment)
                                                            <optgroup label="{{ $treatment->name }}" id="{{ $treatment->type }}" class="{{ $item['type'] != $treatment->type ? 'd-none' : '' }} }}">
                                                                @foreach ($treatment->treatment_details as $item)
                                                                    <option value="{{ $item->id }}" data-price="{{ $item->cost }}">{{ $item->name }} ({{ $item->processing_time }} hari kerja)</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    {{-- <span class="btn-clear p-2">&times;</span> --}}
                                                </div>
                                                <div class="invalid-feedback text-start"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="transaction_details[0][merk]" class="form-label">Merk Item <span class="text-danger">*</span></label>
                                                        <input class="form-control merk" type="text" id="transaction_details[0][merk]" name="transaction_details[0][merk]" placeholder="Cth: Nike" autocomplete="off" required>
                                                        <div class="invalid-feedback text-start"></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="transaction_details[0][size]" class="form-label">Ukuran</label>
                                                    <input type="text" name="transaction_details[0][size]" class="form-control size" autocomplete="off" id="transaction_details[0][size]" placeholder="(Jika Ada)">
                                                    <div class="invalid-feedback text-start"></div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label for="transaction_details[0][description]" class="form-label">Deskripsi Item <span class="text-danger">*</span></label>
                                                <input class="form-control description" type="text" id="transaction_details[0][description]" name="transaction_details[0][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" required>
                                                <div class="invalid-feedback text-start"></div>
                                            </div>
                                            <div class="row d-flex align-items-center">
                                                <div class="col">
                                                    <button type="button" class="btn btn-transparent text-danger btn-remove d-none" id="btn-remove-1"><i class="bx bx-trash bx-sm"></i></button>
                                                    <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus bx-sm"></i></button>
                                                </div>
                                                <div class="col text-end">
                                                    <span class="item-price" id="transaction_details[0][price]">Rp0</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="row mt-5 submit-desktop">
                                    <div class="col">
                                        <a href="/" class="btn btn-outline-my-primary py-2 w-100 btn-back">Batal, Nanti Aja</a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit">Selanjutnya</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="order-summary-desktop position-sticky">
                        <h5 class="fw-bold">Ringkasan Pemesanan</h5>
                        <h6 class="mt-3">Total Item &nbsp;<span class="fw-bold total-item">{{ $transactions['total_item'] ?? '1' }}</span></h6>
                        <hr>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok subtotal">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, ',', '.') : '0' }}</span>
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
                        <div class="row mb-2">
                            <div class="col-6">
                                <h6>Total</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok fw-bold total">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, ',', '.') : '0' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center my-5 pb-2 mobile-footer" style="color: #667085">
                        &copy; {{ date('Y') }} aljushoe <span class="px-1">•</span> All Right Reserved
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
                    <h6>Total Item &nbsp;<span class="fw-bold total-item">{{ $transactions['total_item'] ?? '1' }}</span></h6>
                    <hr>
                    <div class="row mt-3 mb-2">
                        <div class="col-6">
                            <h6>Subtotal</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-inline-blcok subtotal">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, ',', '.') : '0' }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
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
                        <span class="d-inline-blcok fw-bold total">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, ',', '.') : '0' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="/" class="btn btn-outline-my-primary py-2 w-100 btn-back">Batal, Nanti Aja</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-my-primary py-2 w-100 btn-submit">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

@endsection

@section('scripts')
    <script>
        document.querySelector('.btn-expand').addEventListener('click', e => {
            e.preventDefault()
            const summaryMobile = document.querySelector('.summary-mobile')
            e.target.classList.toggle('bx-rotate-180')
            summaryMobile.classList.toggle('show')
        })

        function addField(no) {
            let field = `
                <div class="detail-item item-${no + 1} mb-4">
                    <hr>
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <div class="mb-3 type-select">
                        <select name="transaction_details[${no}][type]" id="transaction_details[${no}][type]" class="form-select type" required>
                            <option value="">Pilih Type Item</option>
                            <option value="sepatu">Sepatu</option>
                            <option value="tas">Tas</option>
                            <option value="sandal">Sandal</option>
                            <option value="topi">Topi</option>
                        </select>
                        <div class="invalid-feedback text-start">Type wajib diisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_details[${no}][treatment_details_id]" class="form-label">Treatment <span class="text-danger">*</span></label>
                        <div class="select-wrapper">
                            <select name="transaction_details[${no}][treatment_details_id]" id="transaction_details[${no}][treatment_details_id]" class="form-select treatment-select" required disabled>
                                <option value="" data-price="0" class="select-type-first">Pilih Type Terlebih Dahulu</option>
                                @foreach ($treatments as $treatment)
                                    <optgroup label="{{ $treatment->name }}" id="{{ $treatment->type }}">
                                        @foreach ($treatment->treatment_details as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->cost }}">{{ $item->name }} ({{ $item->processing_time }} hari kerja)</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="invalid-feedback text-start"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="transaction_details[${no}][merk]" class="form-label">Merk Item <span class="text-danger">*</span></label>
                                <input class="form-control merk" type="text" id="transaction_details[${no}][merk]" name="transaction_details[${no}][merk]" placeholder="Cth: Nike" autocomplete="off" required>
                                <div class="invalid-feedback text-start"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="transaction_details[${no}][size]" class="form-label">Ukuran</label>
                            <input type="text" name="transaction_details[${no}][size]" class="form-control size" autocomplete="off" id="transaction_details[${no}][size]" placeholder="(Jika Ada)">
                            <div class="invalid-feedback text-start"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="transaction_details[${no}][description]" class="form-label">Deskripsi Item <span class="text-danger">*</span></label>
                        <input class="form-control description" type="text" id="transaction_details[${no}][description]" name="transaction_details[${no}][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" required>
                        <div class="invalid-feedback text-start"></div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col">
                            <button type="button" class="btn btn-transparent text-danger btn-remove" id="btn-remove-${no + 1}"><i class="bx bx-trash fs-4"></i></button>
                            <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                        </div>
                        <div class="col text-end">
                            <span class="item-price" id="transaction_details[${no}][price]">Rp0</span>
                        </div>
                    </div>
                </div>
            `;
            return field;
        }
    </script>

    <script src="{{ asset('assets/js/utils/order.js') }}"></script>
@endsection