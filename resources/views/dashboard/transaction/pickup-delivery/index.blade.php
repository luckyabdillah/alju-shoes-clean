@extends('dashboard.layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <h4 class="card-header">Transaksi: Pickup & Delivery</h4>
        <div class="card-body">
            @if (session()->has('success'))
            <div class="flash-data" data-flash="{{ session('success') }}"></div>
            @endif
            <h5>Pickup</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Tanggal Pickup</th>
                            <th class="text-center">Pembayaran</th>
                            <th class="text-center">Status Pembayaran</th>
                            <th class="text-center">Tagihan</th>
                            <th class="text-center">#</th>
                            @can('administrator')
                                <th class="text-center text-danger">DANGER ZONE</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickup_transactions as $pickup_transaction)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $pickup_transaction->customer->name }}</td>
                                <td>{{ date('d M Y, H:i:s', strtotime($pickup_transaction->transaction_start)) }}</td>
                                <td>
                                    @if ($pickup_transaction->proof_of_payment)
                                        <a href="{{ asset('storage/' . $pickup_transaction->proof_of_payment) }}" data-toggle="lightbox" style="text-decoration: underline;">
                                            QRIS
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $pickup_transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $pickup_transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $pickup_transaction->payment_status == 'unpaid' ? 'belum bayar' : 'lunas' }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($pickup_transaction->total_cost, 0, '.', ',') }}</td>
                                <td>
                                    <a href="/transaction/{{ $pickup_transaction->uuid }}" target="_blank" class="btn btn-dark badge my-badge me-1">
                                        <i class="bx bx-printer"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-transaction-details me-1"
                                        data='{
                                            "uuid":"{{ $pickup_transaction->uuid }}",
                                            "invoiceNo":"{{ $pickup_transaction->invoice_no }}",
                                            "customer":"{{ $pickup_transaction->customer->name }}",
                                            "type":"Pickup",
                                            "date":"{{ date('d M Y, H:i:s', strtotime($pickup_transaction->transaction_start)) }}",
                                            "totalItems":"{{ $pickup_transaction->total_items }}",
                                            "totalCost":{{ $pickup_transaction->total_cost }},
                                            "transactionDetails":{{ $pickup_transaction->transaction_details }},
                                            "whatsappNumber":"{{ $pickup_transaction->customer->whatsapp_number }}",
                                            "address":"{{ $pickup_transaction->customer->address }}",
                                            "benchmark":"{{ $pickup_transaction->customer->benchmark }}",
                                            "coordinate":"{{ $pickup_transaction->customer->lat }},{{ $pickup_transaction->customer->long }}"
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $pickup_transaction->uuid }}/status-update" method="post" class="d-inline">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-confirm btn-warning badge my-badge">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>
                                    @can('administrator')
                                        <td>
                                            <form action="/dashboard/transaction/pickup-delivery/{{ $pickup_transaction->uuid }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-delete">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h5 class="mt-5">Delivery</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Tanggal Delivery</th>
                            <th class="text-center">Pembayaran</th>
                            <th class="text-center">Status Pembayaran</th>
                            <th class="text-center">Tagihan</th>
                            <th class="text-center">#</th>
                            @can('administrator')
                                <th class="text-center text-danger">DANGER ZONE</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($delivery_transactions as $delivery_transaction)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $delivery_transaction->customer->name }}</td>
                                <td>{{ date('d M Y, H:i:s', strtotime($delivery_transaction->transaction_end)) }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $delivery_transaction->proof_of_payment) }}" data-toggle="lightbox" style="text-decoration: underline;">
                                        QRIS
                                    </a>
                                </td>
                                <td>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $delivery_transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $delivery_transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $delivery_transaction->payment_status == 'unpaid' ? 'belum bayar' : 'lunas' }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($delivery_transaction->total_cost, 0, '.', ',') }}</td>
                                <td>
                                    <a href="/transaction/{{ $delivery_transaction->uuid }}" target="_blank" class="btn btn-dark badge my-badge me-1">
                                        <i class="bx bx-printer"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-transaction-details me-1"
                                        data='{
                                            "uuid":"{{ $delivery_transaction->uuid }}",
                                            "invoiceNo":"{{ $delivery_transaction->invoice_no }}",
                                            "customer":"{{ $delivery_transaction->customer->name }}",
                                            "type":"Delivery",
                                            "date":"{{ date('d M Y, H:i:s', strtotime($delivery_transaction->transaction_end)) }}",
                                            "totalItems":"{{ $delivery_transaction->total_items }}",
                                            "totalCost":{{ $delivery_transaction->total_cost }},
                                            "transactionDetails":{{ $delivery_transaction->transaction_details }},
                                            "whatsappNumber":"{{ $delivery_transaction->customer->whatsapp_number }}",
                                            "address":"{{ $delivery_transaction->customer->address }}",
                                            "benchmark":"{{ $delivery_transaction->customer->benchmark }}",
                                            "coordinate":"{{ $delivery_transaction->customer->lat }},{{ $delivery_transaction->customer->long }}"
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-success badge my-badge btn-delivery-done"
                                        data-uuid="{{ $delivery_transaction->uuid }}"
                                    >
                                        <i class="bx bx-check"></i>
                                    </button>
                                    @can('administrator')
                                        <td>
                                            <form action="/dashboard/transaction/pickup-delivery/{{ $delivery_transaction->uuid }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-delete">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="detailModalLabel">Detail Transaksi</h2>
                </div>
                <div class="modal-body" style="font-size: 1.05em">
                    <div class="mb-3">
                        <label for="invoice_no" class="form-label fw-semibold">No. Invoice</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Nama Customer</label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="type" class="form-label fw-semibold">Type</label>
                            <input type="text" class="form-control" name="type" id="type" readonly>
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="date" class="form-label fw-semibold">Tanggal</label>
                            <input type="text" class="form-control" name="date" id="date" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 mb-3">
                            <label for="total_items" class="form-label fw-semibold">Total Item</label>
                            <input type="text" class="form-control" name="total_items" id="total_items" readonly>
                        </div>
                        <div class="col-7 mb-3">
                            <label for="total_cost" class="form-label fw-semibold">Tagihan</label>
                            <input type="text" class="form-control" name="total_cost" id="total_cost" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" name="address" id="address" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="benchmark" class="form-label fw-semibold">Detail Lainnya</label>
                        <input type="text" class="form-control" name="benchmark" id="benchmark" readonly>
                    </div>
                    <div class="details-container">
                        <strong>Detail Item:</strong>
                        <ul class="d-block mt-1" id="transaction_details" style="list-style-type: disc;">

                        </ul>
                    </div>
                    <div class="text-end">
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-whatsapp" target="_blank"><i class="bx-fw bx bxl-whatsapp text-white mb-1"></i></a>
                    <a class="btn btn-warning btn-location" target="_blank"><i class="bx-fw bx bxs-map text-white mb-1"></i></a>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transactionDoneModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="transactionDoneModalLabel">Bukti penerimaan</h2>
                </div>
                <div class="modal-body" style="font-size: 1.1em">
                    <form method="post" enctype="multipart/form-data" id="transasctionDoneForm">
                        @method('put')
                        @csrf
                        <input type="hidden" name="uuid" id="uuid" value="{{ old('uuid') }}">
                        <div class="mb-3">
                            <label for="proof_of_handover" class="form-label">Upload Gambar</label>
                            <input class="form-control @error('proof_of_handover') is-invalid @enderror" type="file" id="proof_of_handover" name="proof_of_handover" required>
                            @error('proof_of_handover')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="text-end mt-3">
                            <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $('.data-table').DataTable({
            autoWidth: false,
            initComplete: function() {
                $(this.api().table().container()).find('input').attr('autocomplete', 'off');
            },
        });
        
        $(document).on('click', '.btn-transaction-details', function(e) {
            e.preventDefault();
            let data = JSON.parse($(this).attr('data'));

            // $('#detailModalLabel').text('Detail: ' + data.invoiceNo);

            $('#invoice_no').val(data.invoiceNo);
            $('#customer_name').val(data.customer);
            $('#type').val(data.type);
            $('#date').val(data.date)
            $('#total_items').val(data.totalItems);
            $('#total_cost').val('IDR ' + data.totalCost.toLocaleString('en-US'));
            $('#address').text(data.address);
            $('#benchmark').val(data.benchmark);

            if (data.type == 'Pickup') {
                $('.btn-whatsapp').attr('href', `https://wa.me/${data.whatsappNumber}?text=Halo Kak ${data.customer}, kami dari tim Alju Shoes Clean sedang dalam perjalanan untuk menjemput item Kakak. Siap-siap ya!`);
            } else {
                $('.btn-whatsapp').attr('href', `https://wa.me/${data.whatsappNumber}?text=Halo Kak ${data.customer}, kami dari tim Alju Shoes Clean sedang dalam perjalanan untuk mengantar item Kakak. Siap-siap ya!`);
            }

            // const address = document.querySelector('[name="address"]');
            // address.style.height = 'auto';
            // address.style.height = address.scrollHeight + 2 + 'px';

            // document.querySelector('[name="special_request"]').addEventListener('input', function(e) {
            //     e.target.style.height = e.target.scrollHeight + 2 + 'px';
            // });

            $('.btn-location').attr('href', `https://maps.google.com/?q=${data.coordinate}`);

            const capitalizeWord = (string) => {
                const firstChar = string.charAt(0).toUpperCase();
                const remainingChars = string.slice(1);
                return `${firstChar}${remainingChars}`;
            }
            
            let details = data.transactionDetails;
            let detail = '';
            details.forEach((items, i) => {
                detail += `<li>${capitalizeWord(items.type)} ${items.merk}${(items.size ? ', ' + items.size : '')} <span class="fw-semibold">(${items.treatment_name})</span></li>`;
            })

            $('#transaction_details').html(detail);

            $('#detailModal').modal('show');
        });
    </script>

    @error('proof_of_handover')
        <script>
            window.onload = function() {
                let uuid = $('#uuid').val();
                $('#transasctionDoneForm').attr('action', `/dashboard/transaction/pickup-delivery/${uuid}/status-update`);
                $('#transactionDoneModal').modal('show');
            }
        </script>
    @enderror
@endsection