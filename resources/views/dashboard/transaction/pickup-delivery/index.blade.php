@extends('dashboard.layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <h4 class="card-header">Transaction Pending : Pickup & Delivery</h4>
        <div class="card-body">
            @if (session()->has('success'))
            <div class="flash-data" data-flash="{{ session('success') }}"></div>
            @endif
            <h5>Pickup</h5>
            <div class="table-responsive text-nowrap">
                <table class="table display" id="">
                    <thead>
                        <tr class="text-center">
                            <th class="text-start">#</th>
                            <th class="text-start">Invoice</th>
                            <th class="text-start">Customer</th>
                            <th>Pickup</th>
                            <th>Payment</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                            <th>Complete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($pickup_transactions->count())
                            @foreach ($pickup_transactions as $pickup_transaction)
                            <tr class="text-center">
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $pickup_transaction->invoice_no }}</td>
                                <td class="text-start">{{ $pickup_transaction->customer->name }}</td>
                                <td>{{ date_format(date_create($pickup_transaction->transaction_start), 'd M Y, H:i:s') }}</td>
                                <td>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $pickup_transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $pickup_transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $pickup_transaction->payment_status }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($pickup_transaction->total_amount, 0, '.', ',') }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-dark badge my-badge btn-print" data-invoice="{{ $pickup_transaction->invoice_no }}"><i class="bx bx-printer"></i></a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-detail-pending"
                                        data='{
                                            "uuid":"{{ $pickup_transaction->uuid }}",
                                            "invoiceNo":"{{ $pickup_transaction->invoice_no }}",
                                            "customer":"{{ $pickup_transaction->customer->name }}",
                                            "outlet":"Pickup / Penjemputan",
                                            "start":"{{ date_format(date_create($pickup_transaction->transaction_start), 'd M Y, H:i:s') }}",
                                            "end":"{{ date_format(date_create($pickup_transaction->transaction_end), 'd M Y, H:i:s') }}",
                                            "totalItems":"{{ $pickup_transaction->total_items }}",
                                            "totalAmount":{{ $pickup_transaction->total_amount }},
                                            "detailTransactions":{{ $pickup_transaction->detail_transactions }}
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <a
                                        href="https://www.google.com/maps/place/{{ $pickup_transaction->customer->lat }},{{ $pickup_transaction->customer->long }}"
                                        target="_blank"
                                        class="btn btn-danger badge my-badge">
                                            <i class="bx bxs-map"></i>
                                    </a>
                                    <a 
                                        href="https://wa.me/{{ $pickup_transaction->customer->whatsapp_number }}?text=Halo Kak {{ $pickup_transaction->customer->name }}, kami dari tim Alju Shoes Clean sedang dalam perjalanan untuk mengambil item Kakak. Siap-siap ya!" 
                                        target="_blank" 
                                        class="btn btn-warning badge my-badge">
                                            <i class="bx bxl-whatsapp"></i>
                                    </a>
                                    <a 
                                        href="https://wa.me/{{ $pickup_transaction->customer->whatsapp_number }}?text=Halo Kak {{ $pickup_transaction->customer->name }}, kami sudah sampai di lokasi ya" 
                                        target="_blank" 
                                        class="btn btn-success badge my-badge">
                                        <i class="bx bxl-whatsapp"></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $pickup_transaction->uuid }}/status-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-status btn-primary badge my-badge">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="9">No pending pickup transaction.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <br>
            <h5>Delivery</h5>
            <div class="table-responsive text-nowrap">
                <table class="table display" id="">
                    <thead>
                        <tr class="text-center">
                            <th class="text-start">#</th>
                            <th class="text-start">Invoice</th>
                            <th class="text-start">Customer</th>
                            <th>Delivery</th>
                            <th>Payment</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                            <th>Complete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($delivery_transactions->count())
                            @foreach ($delivery_transactions as $delivery_transaction)
                            <tr class="text-center">
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $delivery_transaction->invoice_no }}</td>
                                <td class="text-start">{{ $delivery_transaction->customer->name }}</td>
                                <td>{{ date_format(date_create($delivery_transaction->transaction_end), 'd M Y, H:i:s') }}</td>
                                <td>
                                    <form action="/dashboard/transaction/pickup-delivery/{{ $delivery_transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $delivery_transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $delivery_transaction->payment_status }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($delivery_transaction->total_amount, 0, '.', ',') }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-dark badge my-badge btn-print" data-invoice="{{ $delivery_transaction->invoice_no }}"><i class="bx bx-printer"></i></a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-detail-pending"
                                        data='{
                                            "uuid":"{{ $delivery_transaction->uuid }}",
                                            "invoiceNo":"{{ $delivery_transaction->invoice_no }}",
                                            "customer":"{{ $delivery_transaction->customer->name }}",
                                            "outlet":"Delivery / Pengantaran",
                                            "start":"{{ date_format(date_create($delivery_transaction->transaction_start), 'd M Y, H:i:s') }}",
                                            "end":"{{ date_format(date_create($delivery_transaction->transaction_end), 'd M Y, H:i:s') }}",
                                            "totalItems":"{{ $delivery_transaction->total_items }}",
                                            "totalAmount":{{ $delivery_transaction->total_amount }},
                                            "detailTransactions":{{ $delivery_transaction->detail_transactions }}
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <a
                                        href="https://www.google.com/maps/place/{{ $delivery_transaction->customer->lat }},{{ $delivery_transaction->customer->long }}"
                                        target="_blank"
                                        class="btn btn-danger badge my-badge">
                                            <i class="bx bxs-map"></i>
                                    </a>
                                    <a 
                                        href="https://wa.me/{{ $delivery_transaction->customer->whatsapp_number }}?text=Halo Kak {{ $delivery_transaction->customer->name }}, kami dari tim Alju Shoes Clean sedang dalam perjalanan untuk mengantar item Kakak. Siap-siap ya!" 
                                        target="_blank" 
                                        class="btn btn-warning badge my-badge">
                                            <i class="bx bxl-whatsapp"></i>
                                    </a>
                                    <a 
                                        href="https://wa.me/{{ $delivery_transaction->customer->whatsapp_number }}?text=Halo Kak {{ $delivery_transaction->customer->name }}, kami sudah sampai di lokasi ya" 
                                        target="_blank" 
                                        class="btn btn-success badge my-badge">
                                        <i class="bx bxl-whatsapp"></i>
                                    </a>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-primary badge my-badge btn-delivery-done"
                                        data-uuid="{{ $delivery_transaction->uuid }}"
                                    >
                                        <i class="bx bx-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="9">No pending delivery transaction.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="detailModalLabel"></h2>
                </div>
                <div class="modal-body" style="font-size: 1.1em">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Customer Name </strong>: <span id="customer-name"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Type </strong>: <span id="outlet"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Start Date </strong>: <span id="start-date"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>End Date </strong>: <span id="end-date"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Total Items </strong>: <span id="total-items"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Total Amount </strong>: <span id="total-amount"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Detail Transaction </strong>:
                            {{-- <span class="d-block mt-1" id="detail-transaction"></span> --}}
                            <ul class="d-block mt-1" id="detail-transaction" style="list-style-type: disc;">

                            </ul>
                        </li>
                    </ul>
                    <div class="text-end">
                        <button class="btn btn-secondary mt-3" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    </div>
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
                            <label for="picture_evidence" class="form-label">Upload Image</label>
                            <input class="form-control @error('picture_evidence') is-invalid @enderror" type="file" id="picture_evidence" name="picture_evidence" required>
                            @error('picture_evidence')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-dark me-2">Submit</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- @error('picture_evidence')
        <p>oi, hehe.</p>
        {{ $message }}
    @enderror --}}

    @error('picture_evidence')
        @section('scripts')
            <script>
                window.onload = function() {
                    let uuid = $('#uuid').val();
                    $('#transasctionDoneForm').attr('action', `/dashboard/transaction/pickup-delivery/${uuid}/status-update`);
                    $('#transactionDoneModal').modal('show');
                }
            </script>
        @endsection
    @enderror

</div>
@endsection