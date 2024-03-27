@extends('layouts.main')

@section('content')
    
    <nav class="order">
        <div class="container mt-4 mb-5">
            @if (session()->has('failed'))
                <div class="flash-data-failed" data-flash="{{ session('failed') }}"></div>
            @endif
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
                            <form action="/order?o={{ request('o') }}" method="post">
                                @csrf
                                @if (request('o'))
                                    <input type="hidden" name="outlet_uuid" value="{{ request('o') }}">
                                @endif
                                <input type="hidden" name="transaction_type" value="dropoff">
                                <div class="item-container">
                                    @if ($transactions)
                                        @foreach ($transactions['detail_transactions'] as $item)
                                        <div class="detail-item item-{{ $loop->iteration }} mb-4">
                                            <label class="form-label">Type</label>
                                            <div class="mb-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="detail_transactions[{{ $loop->iteration - 1 }}][type]" id="detail_transactions[{{ $loop->iteration - 1 }}][sepatu]" value="sepatu" {{ ($item['type'] === 'sepatu') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="detail_transactions[{{ $loop->iteration - 1 }}][sepatu]">Sepatu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="detail_transactions[{{ $loop->iteration - 1 }}][type]" id="detail_transactions[{{ $loop->iteration - 1 }}][tas]" value="tas" {{ ($item['type'] === 'tas') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="detail_transactions[{{ $loop->iteration - 1 }}][tas]">Tas</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="detail_transactions[{{ $loop->iteration - 1 }}][type]" id="detail_transactions[{{ $loop->iteration - 1 }}][sendal]" value="sendal" {{ ($item['type'] === 'sendal') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="detail_transactions[{{ $loop->iteration - 1 }}][sendal]">Sendal</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="detail_transactions[{{ $loop->iteration - 1 }}][treatment_detail_id]" class="form-label">Treatment</label>
                                                <select name="detail_transactions[{{ $loop->iteration - 1 }}][treatment_detail_id]" id="detail_transactions[{{ $loop->iteration - 1 }}][treatment_detail_id]" class="form-select treatment-select" required>
                                                    <option value="" data-price="0">Pilih Treatment</option>
                                                    @foreach ($treatments as $treatment)
                                                        <optgroup label="{{ $treatment->name }}">
                                                            @foreach ($treatment->detail_treatments as $detail)
                                                                <option value="{{ $detail->id }}" data-price="{{ $detail->cost }}" {{ ($item['treatment_detail_id'] == $detail->id) ? 'selected' : '' }}>{{ $detail->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="detail_transactions[{{ $loop->iteration - 1 }}][merk]" class="form-label">Merk Item</label>
                                                        <input class="form-control" type="text" id="detail_transactions[{{ $loop->iteration - 1 }}][merk]" name="detail_transactions[{{ $loop->iteration - 1 }}][merk]" placeholder="Cth: Nike" autocomplete="off" value="{{ $item['merk'] }}" required>
                                                        <div class="invalid-feedback text-start">
                                                            <span class="merk-{{ $loop->iteration - 1 }}-error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="detail_transactions[{{ $loop->iteration - 1 }}][size]" class="form-label">Ukuran</label>
                                                    <input type="text" name="detail_transactions[{{ $loop->iteration - 1 }}][size]" class="form-control" autocomplete="off" id="detail_transactions[{{ $loop->iteration - 1 }}][size]" placeholder="(Jika Ada)" value="{{ $item['size'] }}">
                                                    <div class="invalid-feedback text-start">
                                                        <span class="size-{{ $loop->iteration - 1 }}-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="detail_transactions[{{ $loop->iteration - 1 }}][description]" class="form-label">Deskripsi Item</label>
                                                <input class="form-control" type="text" id="detail_transactions[{{ $loop->iteration - 1 }}][description]" name="detail_transactions[{{ $loop->iteration - 1 }}][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" value="{{ $item['description'] }}" required>
                                                <div class="invalid-feedback text-start">
                                                    <span class="description-{{ $loop->iteration - 1 }}-error"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <button type="button" class="btn btn-transparent text-danger btn-remove d-none" id="btn-remove-1"><i class="bx bx-trash fs-4"></i></button>
                                                    <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                                                </div>
                                                <div class="col text-end">
                                                    <span class="item-price" id="detail_transactions[{{ $loop->iteration - 1 }}][price]">Rp{{ number_format($item['cost'], 0, '.', ',') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                    <div class="detail-item item-1 mb-4">
                                        <label class="form-label">Type</label>
                                        <div class="mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="detail_transactions[0][type]" id="detail_transactions[0][sepatu]" value="sepatu" checked>
                                                <label class="form-check-label" for="detail_transactions[0][sepatu]">Sepatu</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="detail_transactions[0][type]" id="detail_transactions[0][tas]" value="tas">
                                                <label class="form-check-label" for="detail_transactions[0][tas]">Tas</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="detail_transactions[0][type]" id="detail_transactions[0][sendal]" value="sendal">
                                                <label class="form-check-label" for="detail_transactions[0][sendal]">Sendal</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="detail_transactions[0][treatment_detail_id]" class="form-label">Treatment</label>
                                            <select name="detail_transactions[0][treatment_detail_id]" id="detail_transactions[0][treatment_detail_id]" class="form-select treatment-select" required>
                                                <option value="" data-price="0">Pilih Treatment</option>
                                                @foreach ($treatments as $treatment)
                                                    <optgroup label="{{ $treatment->name }}">
                                                        @foreach ($treatment->detail_treatments as $item)
                                                            <option value="{{ $item->id }}" data-price="{{ $item->cost }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="detail_transactions[0][merk]" class="form-label">Merk Item</label>
                                                    <input class="form-control" type="text" id="detail_transactions[0][merk]" name="detail_transactions[0][merk]" placeholder="Cth: Nike" autocomplete="off" required>
                                                    <div class="invalid-feedback text-start">
                                                        <span class="merk-0-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="detail_transactions[0][size]" class="form-label">Ukuran</label>
                                                <input type="text" name="detail_transactions[0][size]" class="form-control" autocomplete="off" id="detail_transactions[0][size]" placeholder="(Jika Ada)">
                                                <div class="invalid-feedback text-start">
                                                    <span class="size-0-error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="detail_transactions[0][description]" class="form-label">Deskripsi Item</label>
                                            <input class="form-control" type="text" id="detail_transactions[0][description]" name="detail_transactions[0][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" required>
                                            <div class="invalid-feedback text-start">
                                                <span class="description-0-error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <button type="button" class="btn btn-transparent text-danger btn-remove d-none" id="btn-remove-1"><i class="bx bx-trash fs-4"></i></button>
                                                <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                                            </div>
                                            <div class="col text-end">
                                                <span class="item-price" id="detail_transactions[0][price]">Rp0</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="row mt-5">
                                    <div class="col">
                                        <button class="btn btn-outline-my-primary py-2 w-100">Batal, Nanti Aja</button>
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
                    <div class="sticky-top" style="top: 80px">
                        <h5 class="fw-bold">Ringkasan Pemesanan</h5>
                        <h6 class="mt-3">Total Item &nbsp;<span class="fw-bold total-item">{{ $transactions['total_item'] ?? '1' }}</span></h6>
                        <hr>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok subtotal">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, '.', ',') : '0' }}</span>
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
                                <span class="d-inline-blcok fw-bold total">Rp{{ isset($transactions['cost']) ? number_format($transactions['cost'], 0, '.', ',') : '0' }}</span>
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
        function addField(no) {
            let field = `
                <div class="detail-item item-${no + 1} mb-4">
                    <hr>
                    <label class="form-label">Type</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="detail_transactions[${no}][type]" id="detail_transactions[${no}][sepatu]" value="sepatu" checked>
                            <label class="form-check-label" for="detail_transactions[${no}][sepatu]">Sepatu</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="detail_transactions[${no}][type]" id="detail_transactions[${no}][tas]" value="tas">
                            <label class="form-check-label" for="detail_transactions[${no}][tas]">Tas</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="detail_transactions[${no}][type]" id="detail_transactions[${no}][sendal]" value="sendal">
                            <label class="form-check-label" for="detail_transactions[${no}][sendal]">Sendal</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="detail_transactions[${no}][treatment_detail_id]" class="form-label">Treatment</label>
                        <select name="detail_transactions[${no}][treatment_detail_id]" id="detail_transactions[${no}][treatment_detail_id]" class="form-select treatment-select" required>
                            <option value="" data-price="0">Pilih Treatment</option>
                            @foreach ($treatments as $treatment)
                                <optgroup label="{{ $treatment->name }}">
                                    @foreach ($treatment->detail_treatments as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->cost }}">{{ $item->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="detail_transactions[${no}][merk]" class="form-label">Merk Item</label>
                                <input class="form-control" type="text" id="detail_transactions[${no}][merk]" name="detail_transactions[${no}][merk]" placeholder="Cth: Nike" autocomplete="off" required>
                                <div class="invalid-feedback text-start">
                                    <span class="merk-${no}-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="detail_transactions[${no}][size]" class="form-label">Ukuran</label>
                            <input type="text" name="detail_transactions[${no}][size]" class="form-control" autocomplete="off" id="detail_transactions[${no}][size]" placeholder="(Jika Ada)">
                            <div class="invalid-feedback text-start">
                                <span class="size-${no}-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="detail_transactions[${no}][description]" class="form-label">Deskripsi Item</label>
                        <input class="form-control" type="text" id="detail_transactions[${no}][description]" name="detail_transactions[${no}][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" required>
                        <div class="invalid-feedback text-start">
                            <span class="description-${no}-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-transparent text-danger btn-remove" id="btn-remove-${no + 1}"><i class="bx bx-trash fs-4"></i></button>
                            <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                        </div>
                        <div class="col text-end">
                            <span class="item-price" id="detail_transactions[${no}][price]">Rp0</span>
                        </div>
                    </div>
                </div>
            `;
            return field;
        }
    </script>

    <script src="{{ asset('assets/js/order-script.js') }}"></script>
@endsection