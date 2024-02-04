@extends('dashboard.layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <h4 class="card-header">Transaction Pending : Dropzone</h4>
        <div class="card-body">
            {{-- <h6><a href="/dashboard/master-data/campaign/create" class="btn btn-dark">Add New Campaign</a></h6> --}}
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <span class="text-black">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive text-nowrap">
                <table class="table display" id="">
                    <thead>
                        <tr class="text-center">
                            <th class="text-start">#</th>
                            <th class="text-start">Invoice</th>
                            <th class="text-start">Customer</th>
                            <th class="text-start">Outlet</th>
                            <th>Payment</th>
                            <th>Payment Status</th>
                            {{-- <th>Start</th>
                            <th>End</th> --}}
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr class="text-center">
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $transaction->invoice_no }}</td>
                            <td class="text-start">{{ $transaction->customer->name }}</td>
                            <td class="text-start">{{ $transaction->outlet->outlet_name }}</td>
                            <td>{{ ucfirst($transaction->payment_method) }}</td>
                            {{-- <td>{{ date_format(date_create($transaction->transaction_start), 'd-M-Y, H:i:s') }}</td>
                            <td>{{ date_format(date_create($transaction->transaction_end), 'd-M-Y, H:i:s') }}</td> --}}
                            <td>
                                <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}/payment-update" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-{{ $transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                        {{-- <i class="bx bxs-circle text-{{ $campaign->status == 1 ? 'success' : 'danger' }}"></i> --}}
                                        {{ $transaction->payment_status }}
                                    </button>
                                </form>
                            </td>
                            <td>IDR {{ number_format($transaction->total_amount, 0, '.', ',') }}</td>
                            <td>
                                @if ($transaction->transaction_status == 'on_progress')
                                    <button
                                        type="button"
                                        class="border-0 bg-light btn-transaction-done"
                                        data-uuid="{{ $transaction->uuid }}"
                                    >
                                        <i class="bx bxs-circle text-warning fs-4"></i>
                                    </button>
                                @else
                                <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}/status-update" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="border-0 btn-status bg-light">
                                        <i class="bx bxs-circle text-{{ $transaction->transaction_status == 'pending' ? 'danger' : 'dark' }} fs-4"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-dark badge my-badge btn-print" data-invoice="{{ $transaction->invoice_no }}"><i class="bx bx-printer"></i></a>
                                <button
                                    type="button"
                                    class="btn btn-secondary badge my-badge btn-detail-pending"
                                    data='{
                                        "uuid":"{{ $transaction->uuid }}",
                                        "invoiceNo":"{{ $transaction->invoice_no }}",
                                        "customer":"{{ $transaction->customer->name }}",
                                        "outlet":"{{ $transaction->outlet->outlet_name }}",
                                        "start":"{{ date_format(date_create($transaction->transaction_start), 'd M Y, H:i:s') }}",
                                        "end":"{{ date_format(date_create($transaction->transaction_end), 'd M Y, H:i:s') }}",
                                        "totalItems":"{{ $transaction->total_items }}",
                                        "totalAmount":{{ $transaction->total_amount }},
                                        "detailTransactions":{{ $transaction->detail_transactions }}
                                    }'
                                >
                                    <i class="bx bx-show-alt"></i>
                                </button>
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
                    <h2 class="modal-title fs-5" id="detailModalLabel"></h2>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body" style="font-size: 1.1em">
                    {{-- <table border="0">
                        <tr>
                            <td class="fw-bold">Customer Name</td>
                            <td>:</td>
                            <td>&nbsp;</td>
                            <td id="customer-name"></td>
                        <tr>
                        <tr>
                            <td class="fw-bold">Outlet</td>
                            <td>:</td>
                            <td></td>
                            <td id="outlet"></td>
                        <tr>
                        <tr>
                            <td class="fw-bold">Start Date</td>
                            <td>:</td>
                            <td></td>
                            <td id="start-date"></td>
                        <tr>
                        <tr>
                            <td class="fw-bold">End Date</td>
                            <td>:</td>
                            <td></td>
                            <td id="end-date"></td>
                        <tr>
                        <tr>
                            <td class="fw-bold">Total Items</td>
                            <td>:</td>
                            <td></td>
                            <td id="total-items"></td>
                        <tr>
                        <tr>
                            <td class="fw-bold">Total Amount</td>
                            <td>:</td>
                            <td></td>
                            <td id="total-amount"></td>
                        <tr>
                        <tr>
                            <td style="vertical-align: top;" class="fw-bold">Detail Transaction &nbsp;</td>
                            <td style="vertical-align: top;">:</td>
                            <td></td>
                            <td id="detail-transaction">
                            </td>
                        <tr>
                    </table> --}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Customer Name </strong>: <span id="customer-name"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Outlet </strong>: <span id="outlet"></span>
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
                    <h2 class="modal-title fs-5" id="transactionDoneModalLabel">Proof of pickup</h2>
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
                    $('#transasctionDoneForm').attr('action', `/dashboard/transaction/dropzone/${uuid}/status-update`);
                    $('#transactionDoneModal').modal('show');
                }
            </script>
        @endsection
    @enderror

</div>
@endsection