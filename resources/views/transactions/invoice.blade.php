@extends('layouts.main')

@section('content')
    
    <nav class="summary">
        @if (session()->has('success'))
            <div class="flash-data" data-flash="{{ session('success') }}"></div>
        @endif
        <div class="container mt-4 mb-5">
            <div class="card w-100 p-4 mb-5">
                <div class="row pb-3">
                    <div class="col-md-6">
                        <table border="0" cellpadding="0">
                            <tr>
                                <td class="pb-1">No. Invoice</td>
                                <td class="pb-1 fw-bold">{{ $transaction->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td class="pb-1 pe-4">Tanggal Invoice</td>
                                <td class="pb-1 fw-bold">{{ date('d M Y', strtotime($transaction->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td class="pb-1">Pemesan</td>
                                <td class="pb-1 fw-bold">{{ $transaction->customer->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col text-end invoice-total-transaction">
                        Total Transaksi &nbsp;&nbsp;<span class="fw-bold">Rp{{ number_format($transaction->total_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
                <hr>
                @if ($transaction->transaction_type == 'pickup-delivery')
                    <div class="mt-3 mb-3">
                        <h6 class="mb-2 m-0">Tanggal Penjemputan</h6>
                        <h6 class="m-0 fw-bold">{{ date('d-m-Y, H:i', strtotime($transaction->transaction_start)) }}</h6>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-2 m-0">Tanggal Antar</h6>
                        <h6 class="m-0 fw-bold">
                            {{ date('d-m-Y, H:i', strtotime($transaction->transaction_end)) }}
                            @if (strtotime($transaction->transaction_end) - strtotime('now') > 86400)
                                <span>
                                    <a href="javascript:void(0)" class="link-danger link-underline-opacity-0 ms-2" data-bs-toggle="modal" data-bs-target="#menuModal">Ubah jadwal</a></td>
                                </span>
                            @endif
                        </h6>
                    </div>
                @else
                    <div class="mt-3 mb-3">
                        <h6 class="mb-2 m-0">Tanggal Mulai</h6>
                        <h6 class="m-0 fw-bold">{{ date('d-m-Y, H:i', strtotime($transaction->transaction_start)) }}</h6>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-2 m-0">Tanggal Selesai</h6>
                        <h6 class="m-0 fw-bold">
                            {{ date('d-m-Y, H:i', strtotime($transaction->transaction_end)) }}
                        </h6>
                    </div>
                @endif
                <hr>
                <table class="table mt-4 summary-item-desktop">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Merk</th>
                        <th>Deskripsi</th>
                        <th>Treatment</th>
                        <th class="text-end">Harga</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->transaction_details as $item)
                            <tr>
                                <td class="py-3">{{ $loop->iteration }}</td>
                                <td class="py-3">{{ ucfirst($item['type']) }}</td>
                                <td class="py-3 text-nowrap">{{ $item['merk'] }} &nbsp; <span style="border-right: {{ $item['size'] ? '1px' : '0' }} solid rgb(196, 196, 196)"></span> &nbsp; <small class="text-secondary fw-semibold">{{ $item['size'] ? 'Size ' : '' }}</small>{{ $item['size'] ?? '' }}</td>
                                <td class="py-3">{{ $item->description }}</td>
                                <td class="py-3">{{ $item->treatment_name }}</td>
                                <td class="py-3 text-end text-nowrap">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="summary-item-mobile">
                    @foreach ($transaction->transaction_details as $item)
                        <div class="py-3">
                            <h6 class="fw-bold text-danger">Item {{ $loop->iteration }} &nbsp; <span class="text-dark">{{ ucfirst($item['type']) }}</span></h6>
                            <p class="mb-1 fw-medium"><span class="text-secondary">Merk:</span> {{ $item['merk'] }}&nbsp; <span style="border-right: {{ $item['size'] ? '1px' : '0' }} solid rgb(196, 196, 196)"></span> &nbsp; <span class="text-secondary fw-semibold">{{ $item['size'] ? 'Size ' : '' }}</span>{{ $item['size'] ?? '' }}</p>
                            <p class="mb-1 fw-medium"><span class="text-secondary">Desc:</span> {{ $item['description'] }}</p>
                            <p class="mb-1 fw-medium"><span class="text-secondary">Treatment:</span> {{ $item['treatment_name'] }}</p>
                            <p class="mb-1 fw-medium"><span class="text-secondary">Harga:</span> Rp {{ number_format($item['cost'], 0, ',', '.') }}</p>
                        </div>
                        <hr>
                    @endforeach
                </div>
                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">
                        <table border="0" cellpadding="0" style="width: 100%">
                            <tr>
                                <td class="pb-2">Metode Pembayaran</td>
                                <td class="pb-2 text-end">{{ $transaction->payment_method == 'cash' ? 'Cash/Tunai' : 'QRIS' }}</td>
                            </tr>
                            <tr>
                                <td class="pb-2">Subtotal Item</td>
                                <td class="pb-2 text-end">Rp {{ number_format($transaction->cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="pb-2">Biaya Pickup & Delivery</td>
                                <td class="pb-2 text-end">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            @if ($transaction->treatment_discount)
                                <tr class="text-danger">
                                    <td class="pb-2">Diskon Treatment</td>
                                    <td class="pb-2 text-end">Rp {{ number_format($transaction->treatment_discount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($transaction->delivery_discount)
                                <tr class="text-danger">
                                    <td class="pb-2">Diskon Delivery</td>
                                    <td class="pb-2 text-end">Rp {{ number_format($transaction->delivery_discount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="pb-2 fw-bold">Total</td>
                                <td class="pb-2 text-end fw-bold">Rp {{ number_format($transaction->total_cost, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card w-100 p-4">
                <h6 class="fw-bold">PERHATIAN</h6>
                <ol>
                    <li class="mb-2">Pengambilan barang oleh konsumen harap disertai nota.</li>
                    <li class="mb-2">Pengambilan barang baik di counter maupun diantarkan (delivery) wajib diperiksa oleh konsumen. Kehilangan/kerusakan setelah barang diterima konsumen diluar tanggung jawab Alju Shoes Clean.</li>
                    <li class="mb-2">Barang yang tidak diambli selama 1 bulan terhitung sejak tanggal masuk barang, maka kehilangan dan kerusakan diluar tanggung jawab Alju Shoes Clean.</li>
                    <li class="mb-2">Setiap konsumen dianggap setuju dengan peraturan diatas.</li>
                </ol>
            </div>

            <div class="row my-5">
                <div class="col-md-6 mb-2">
                    <a href="/" class="btn btn-outline-my-primary fw-semibold py-2 w-100">Kembali ke Halaman Utama</a>
                </div>
                <div class="col-md-6 mb-2">
                    <a href="/invoice/{{ $transaction->uuid }}/export" class="btn btn-my-primary fw-semibold py-2 w-100" target="_blank">Print Invoice</a>
                </div>
            </div>

            <div class="text-center mt-3 mobile-footer" style="color: #667085">
                &copy; {{ date('Y') }} aljushoesclean <span class="px-1">•</span> All Right Reserved
            </div>
        </div>
    </nav>

    @if (strtotime($transaction->transaction_end) - strtotime('now') > 86400)
        <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="border: 0">
                        <h1 class="modal-title fs-5 fw-bold" id="menuModalLabel">Ubah Jadwal Pengantaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: unset"></button>
                    </div>
                    <form method="post" id="modalForm" action="/invoice/{{ $transaction->uuid }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="delivery_date" class="form-label">Tanggal ({{ $holidayInDay }} Libur)</label>
                                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ date('Y-m-d', strtotime($transaction->transaction_end)) }}" required autocomplete="off"/>
                                <input type="hidden" id="current_transaction_enddate" name="current_transaction_enddate" value="{{ date('Y-m-d', strtotime($transaction->transaction_end)) }}"/>
                                <input type="hidden" id="holiday_in_number" name="holiday_in_number" value="{{ $holidayInNumber }}"/>
                                <input type="hidden" id="holiday_in_day" name="holiday_in_day" value="{{ $holidayInDay }}"/>
                            </div>
                            <div class="mb-3">
                                <label for="delivery_time" class="form-label">Waktu (10.00 - 17.00)</label>
                                <input type="time" class="form-control @error('delivery_time') is-invalid @enderror" id="delivery_time" name="delivery_time" required autocomplete="off" value="{{ date('H:i', strtotime($transaction->transaction_end)) }}"/>
                            </div>
                        </div>
                        <div class="modal-footer" style="border: 0">
                            <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-my-primary btn-submit" style="min-width: 80px;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        const flashData = $('.flash-data').data('flash');

        if (flashData) {
            Swal({
                title: 'Success',
                text: flashData,
                type: 'success'
            });
        }
        
        const currentTransactionEndDate = $('#current_transaction_enddate').val();
        const currentTransactionEndTime = $('#delivery_time').val();
        const holidayInNumber = $('#holiday_in_number').val();
        const holidayInDay = $('#holiday_in_day').val();
        
        let minDate = new Date(currentTransactionEndDate);
        let ddMinDate = minDate.getDate();
        let mmMinDate = minDate.getMonth() + 1;
        let yyyyMinDate = minDate.getFullYear();

        if (ddMinDate < 10) {
            ddMinDate = '0' + ddMinDate;
        }

        if (mmMinDate < 10) {
            mmMinDate = '0' + mmMinDate;
        }

        let maxDate = new Date(minDate.getTime() + (86400000 * 7));
        let ddMaxDate = maxDate.getDate();
        let mmMaxDate = maxDate.getMonth() + 1;
        let yyyyMaxDate = maxDate.getFullYear();

        if (ddMaxDate < 10) {
            ddMaxDate = '0' + ddMaxDate;
        }

        if (mmMaxDate < 10) {
            mmMaxDate = '0' + mmMaxDate;
        }

        minDate = yyyyMinDate + '-' + mmMinDate + '-' + ddMinDate;
        maxDate = yyyyMaxDate + '-' + mmMaxDate + '-' + ddMaxDate;

        const deliveryDateField = document.getElementById('delivery_date');
        if (deliveryDateField) {
            deliveryDateField.setAttribute('min', minDate);
            deliveryDateField.setAttribute('max', maxDate);
    
            deliveryDateField.addEventListener('input', function(e) {
                const selectedDate = new Date(e.target.value);
                if (Date.parse(selectedDate) < Date.parse(minDate) || Date.parse(selectedDate) > Date.parse(maxDate)) {
                    e.target.value = currentTransactionEndDate;
                    return Swal({
                        title: 'Gagal',
                        text: 'Maksimal tanggal penjemputan 7 hari dari hari selesai',
                        type: 'error'
                    })
                } else if (selectedDate.getDay() == holidayInNumber) {
                    e.target.value = currentTransactionEndDate;
                    return Swal({
                        title: 'Gagal',
                        text: `Tidak bisa memilih hari ${holidayInDay}`,
                        type: 'error'
                    })
                }
            });
        }

        const deliveryTimeField = document.getElementById('delivery_time');
        if (deliveryTimeField) {
            deliveryTimeField.addEventListener('blur', function(e) {
                const selectedDate = new Date(deliveryDateField.value);
                if (selectedDate == 'Invalid Date') {
                    e.target.blur()
                    e.target.value = currentTransactionEndTime;
                    Swal({
                        title: 'Gagal',
                        text: 'Pilih tanggal delivery terlebih dahulu',
                        type: 'error'
                    });
                } else {
                    const selectedTime = parseInt(e.target.value.split(':').join(''));
                    if (selectedTime < 1000 || selectedTime > 1700) {
                        e.target.blur()
                        e.target.value = currentTransactionEndTime;
                        Swal({
                            title: 'Gagal',
                            text: 'Waktu pengantaran antara 10.00 - 17.00',
                            type: 'error'
                        });
                    }
                }
            });
        }

        const form = document.querySelector('#modalForm')
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitButton = document.querySelector('.btn-submit');
                const cancelButton = document.querySelector('.btn-cancel');
                submitButton.setAttribute('disabled', true);
                cancelButton.classList.add('disabled');
                submitButton.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
                form.submit();
            });
        }
    </script>
@endsection