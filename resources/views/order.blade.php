@extends('layouts.main')

@section('content')
    
    <nav class="order">
        <div class="container mt-4 mb-5">
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
                            <form action="/order" method="post">
                                @csrf
                                @if (request('o'))
                                    <input type="hidden" name="outlet_uuid" value="{{ request('o') }}">
                                @endif
                                {{-- @if ($formData)
                                    {{ dd($formData) }}
                                @endif --}}
                                {{-- @if (old('whatsapp_number'))
                                    {{ dd(old('whatsapp_number')) }}
                                @endif --}}
                                <input type="hidden" name="transaction_type" value="dropoff">
                                <div class="item-container">
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
                                                {{-- @foreach ($treatments as $treatment)
                                                <option value="{{ $treatment->id }}" {{ (old('detail_transactions.0.treatment_detail_id', $formData['detail_transactions'][0]['treatment_detail_id'] ?? null) == $treatment->id) ? 'selected' : '' }} data-price="{{ $treatment->cost }}">{{ $treatment->name }}</option>
                                                @endforeach --}}
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
                                                    <input class="form-control @error('detail_transactions.0.merk') is-invalid @enderror" type="text" id="detail_transactions[0][merk]" name="detail_transactions[0][merk]" placeholder="Cth: Nike" autocomplete="off" value="{{ old('detail_transactions.0.merk', $formData['detail_transactions'][0]['merk'] ?? null) }}" required>
                                                    @error('detail_transactions.0.merk')
                                                        <div class="invalid-feedback text-start">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="detail_transactions[0][size]" class="form-label">Ukuran</label>
                                                <input type="text" name="detail_transactions[0][size]" class="form-control @error('detail_transactions.0.size') is-invalid @enderror" autocomplete="off" id="detail_transactions[0][size]" placeholder="(Jika Ada)" value="{{ old('detail_transactions.0.size', $formData['detail_transactions'][0]['size'] ?? null) }}">
                                                @error('detail_transactions.0.size')
                                                    <div class="invalid-feedback text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="detail_transactions[0][description]" class="form-label">Deskripsi Item</label>
                                            <input class="form-control @error('detail_transactions.0.description') is-invalid @enderror" type="text" id="detail_transactions[0][description]" name="detail_transactions[0][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" value="{{ old('detail_transactions.0.description', $formData['detail_transactions'][0]['description'] ?? null) }}" required>
                                            @error('detail_transactions.0.description')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <button type="button" class="btn btn-transparent text-danger btn-remove d-none" id="btn-remove-1"><i class="bx bx-trash fs-4"></i></button>
                                                <button type="button" class="btn btn-my-light text-secondary btn-add"><i class="bx bx-plus fs-4"></i></button>
                                            </div>
                                            <div class="col text-end">
                                                <span class="item-price" id="detail_transactions[0][price]">{{ $formData['detail_transactions'][0]['price'] ?? 'Rp0' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-5">
                                    <div class="col">
                                        <button class="btn btn-outline-my-primary py-2 w-100">Batal, Nanti Aja</button>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-my-primary py-2 w-100">Selanjutnya</button>
                                    </div>
                                </div>

                                {{-- <div class="mb-3 mt-5">
                                    <label for="whatsapp_number" class="form-label">Nomor WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon14">+62</span>
                                        <input class="form-control @error('whatsapp_number') is-invalid @enderror" type="text" id="whatsapp_number" name="whatsapp_number" placeholder="81234567890" autocomplete="off" value="{{ old('whatsapp_number') }}" required>
                                        @error('whatsapp_number')
                                            <div class="invalid-feedback text-start">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <a href="javascript:void(0)" class="btn btn-danger btn-check-number" style="min-width: 80px">Check</a>
                                    </div>
                                    <small class="text-danger">* periksa nomor terdaftar terlebih dahulu</small>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Nama kamu" autocomplete="off" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div> --}}
                                {{-- <div class="mb-3">
                                    <label class="form-label d-block">Metode Pembayaran</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked/>
                                        <label class="form-check-label" for="cash">Cash</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris" />
                                        <label class="form-check-label" for="qris">QRIS</label>
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-top" style="top: 80px">
                        <h5 class="fw-bold">Ringkasan Pemesanan</h5>
                        <h6 class="mt-3">Total Item &nbsp;<span class="fw-bold total-item">1</span></h6>
                        <hr>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-inline-blcok subtotal">Rp0</span>
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
                                <span class="d-inline-blcok fw-bold total">Rp0</span>
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
        let count = 1;
        const maxItems = 10;
        // const addButton = $('.btn-add');
        const wrapper = document.querySelector('.item-container');

        $(document).on('click', '.btn-add', function() {
            let totalFields = wrapper.querySelectorAll('.detail-item').length;
            if (totalFields < maxItems) {
                wrapper.querySelectorAll('.btn-add').forEach(button => {
                    button.classList.add('d-none');
                });
                wrapper.querySelectorAll('.btn-remove').forEach(button => {
                    button.classList.remove('d-none');
                });
                $(wrapper).append(addField(count));
                document.querySelector('.total-item').innerHTML = totalFields + 1;
                let lastField = wrapper.querySelector('.detail-item:last-child');
                lastField.querySelector('.btn-add').classList.remove('d-none');
                count++;
            } else {
                Swal({
                    title: 'Maximum Limit',
                    text: 'Hanya boleh maksimal ' + maxItems + ' item per transaksi',
                    type: 'warning'
                })
            }
            // console.log(count);
        })

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
                                <input class="form-control @error('detail_transactions.${no}.merk') is-invalid @enderror" type="text" id="detail_transactions[${no}][merk]" name="detail_transactions[${no}][merk]" placeholder="Cth: Nike" autocomplete="off" required>
                                @error('detail_transactions.${no}.merk')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="detail_transactions[${no}][size]" class="form-label">Ukuran</label>
                            <input type="text" name="detail_transactions[${no}][size]" class="form-control @error('detail_transactions.${no}.size') is-invalid @enderror" autocomplete="off" id="detail_transactions[${no}][size]" placeholder="(Jika Ada)" >
                            @error('detail_transactions.${no}.size')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="detail_transactions[${no}][description]" class="form-label">Deskripsi Item</label>
                        <input class="form-control @error('detail_transactions.${no}.description') is-invalid @enderror" type="text" id="detail_transactions[${no}][description]" name="detail_transactions[${no}][description]" placeholder="Cth: Warna putih, cover bawah hijau" autocomplete="off" required>
                        @error('detail_transactions.${no}.description')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
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

        $(wrapper).on('click', '.btn-remove', function(e) {
            let parent = $(this).attr('id').replace('btn-remove', 'item');
            removeField(parent);

            let totalFields = wrapper.querySelectorAll('.detail-item').length;
            document.querySelector('.total-item').innerHTML = totalFields;

            let subtotal = 0;
            document.querySelectorAll('.item-price').forEach((element) => {
                let sub = (element.innerHTML).split(',').join('');
                sub = sub.split('Rp').join('');
                subtotal += parseInt(sub);
            })
            document.querySelector('.subtotal').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');
            document.querySelector('.total').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');

            let lastField = wrapper.querySelector('.detail-item:last-child');
            lastField.querySelector('.btn-add').classList.remove('d-none');

            let firstField = wrapper.querySelector('.detail-item:first-child');
            firstField.querySelector('hr').remove();
            if (totalFields === 1) {
                lastField.querySelector('.btn-remove').classList.add('d-none');
                lastField.querySelector('.btn-add').classList.remove('d-none');
                lastField.querySelector('hr').remove();
            }
        })

        function removeField(element) {
            $(`.${element}`).remove();
        }
    </script>

    <script src="{{ asset('assets/js/order-script.js') }}"></script>
@endsection