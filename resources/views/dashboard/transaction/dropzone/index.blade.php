@extends('dashboard.layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <h4 class="card-header">Transaksi: Dropzone</h4>
        <div class="card-body">
            @if (session()->has('success'))
            <div class="flash-data" data-flash="{{ session('success') }}"></div>
            @endif
            @canany(['manager', 'administrator'])
                <div class="row mb-4">
                    <div class="col-md-5">
                        <label for="id" class="form-label">Filter</label>
                        <form method="get">
                            <div class="input-group">
                                <select name="id" id="id" class="form-select">
                                    <option value="">Semua Outlet</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->uuid }}" {{ request('id') == $outlet->uuid ? 'selected' : '' }}>{{ $outlet->outlet_name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcanany
            <h5>Pending</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Outlet</th>
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
                        @foreach ($pending_transactions as $transaction)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $transaction->customer->name }}</td>
                                <td>{{ $transaction->outlet->outlet_name }}</td>
                                @if ($transaction->payment_method == 'cash')
                                    <td>Cash</td>
                                @else
                                    <td>
                                        <a href="{{ asset('storage/' . $transaction->proof_of_payment) }}" data-toggle="lightbox" style="text-decoration: underline;">
                                            QRIS
                                        </a>
                                    </td>
                                @endif
                                <td>
                                    <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $transaction->payment_status == 'unpaid' ? 'belum bayar' : 'lunas' }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($transaction->total_cost, 0, '.', ',') }}</td>
                                <td>
                                    <a href="/transaction/{{ $transaction->uuid }}" target="_blank" class="btn btn-dark badge my-badge me-1">
                                        <i class="bx bx-printer"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-transaction-details me-1"
                                        data='{
                                            "uuid":"{{ $transaction->uuid }}",
                                            "invoiceNo":"{{ $transaction->invoice_no }}",
                                            "customer":"{{ $transaction->customer->name }}",
                                            "outlet":"{{ $transaction->outlet->outlet_name }}",
                                            "start":"{{ date('d M Y, H:i:s', strtotime($transaction->transaction_start)) }}",
                                            "end":"{{ date('d M Y, H:i:s', strtotime($transaction->transaction_end)) }}",
                                            "totalItems":"{{ $transaction->total_items }}",
                                            "totalCost":{{ $transaction->total_cost }},
                                            "paymentStatus":"{{ $transaction->payment_status }}",
                                            "transactionDetails":{{ $transaction->transaction_details }}
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}/status-update" method="post" class="d-inline">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-warning btn-confirm badge my-badge">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>
                                    @can('administrator')
                                        <td>
                                            <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}" method="post" class="d-inline">
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
    <div class="card mt-3">
        <div class="card-body">
            <h5>On Progress</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Outlet</th>
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
                        @foreach ($on_progress_transactions as $transaction)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $transaction->customer->name }}</td>
                                <td>{{ $transaction->outlet->outlet_name }}</td>
                                @if ($transaction->payment_method == 'cash')
                                    <td>Cash</td>
                                @else
                                    <td>
                                        <a href="{{ asset('storage/' . $transaction->proof_of_payment) }}" data-toggle="lightbox" style="text-decoration: underline;">
                                            QRIS
                                        </a>
                                    </td>
                                @endif
                                <td>
                                    <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}/payment-update" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-{{ $transaction->payment_status == 'unpaid' ? 'danger' : 'success' }} btn-status badge my-badge">
                                            {{ $transaction->payment_status == 'unpaid' ? 'belum bayar' : 'lunas' }}
                                        </button>
                                    </form>
                                </td>
                                <td>IDR {{ number_format($transaction->total_cost, 0, '.', ',') }}</td>
                                <td>
                                    <a href="/transaction/{{ $transaction->uuid }}" target="_blank" class="btn btn-dark badge my-badge me-1">
                                        <i class="bx bx-printer"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary badge my-badge btn-transaction-details me-1"
                                        data='{
                                            "uuid":"{{ $transaction->uuid }}",
                                            "invoiceNo":"{{ $transaction->invoice_no }}",
                                            "customer":"{{ $transaction->customer->name }}",
                                            "outlet":"{{ $transaction->outlet->outlet_name }}",
                                            "start":"{{ date('d M Y, H:i:s', strtotime($transaction->transaction_start)) }}",
                                            "end":"{{ date('d M Y, H:i:s', strtotime($transaction->transaction_end)) }}",
                                            "totalItems":"{{ $transaction->total_items }}",
                                            "totalCost":{{ $transaction->total_cost }},
                                            "paymentStatus":"{{ $transaction->payment_status }}",
                                            "transactionDetails":{{ $transaction->transaction_details }}
                                        }'
                                    >
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-success btn-transaction-done badge my-badge"
                                        data-uuid="{{ $transaction->uuid }}"
                                    >
                                        <i class="bx bx-check"></i>
                                    </button>
                                    @can('administrator')
                                        <td>
                                            <form action="/dashboard/transaction/dropzone/{{ $transaction->uuid }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-delete">
                                                    {{-- <i class="bx bx-trash"></i> --}}
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
</div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="detailModalLabel">Detail Transaksi</h2>
                </div>
                <div class="modal-body" style="font-size: 1.05em">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_no" class="form-label fw-semibold">No. Invoice</label>
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="outlet" class="form-label fw-semibold">Outlet</label>
                            <input type="text" class="form-control" name="outlet" id="outlet" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Nama Customer</label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="text" class="form-control" name="start_date" id="start_date" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="text" class="form-control" name="end_date" id="end_date" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="total_items" class="form-label fw-semibold">Total Item</label>
                            <input type="text" class="form-control" name="total_items" id="total_items" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="total_cost" class="form-label fw-semibold">Tagihan &nbsp; <span id="payment_status" class="badge my-badge pb-2" style="font-size: .8rem"></span></label>
                            <input type="text" class="form-control" name="total_cost" id="total_cost" readonly>
                        </div>
                    </div>
                    <div class="details-container">
                        <strong>Detail Item:</strong>
                        <ul class="d-block mt-1" id="transaction_details" style="list-style-type: disc;">

                        </ul>
                    </div>
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
                    <h2 class="modal-title fs-5" id="transactionDoneModalLabel">Bukti pengambilan</h2>
                </div>
                <div class="modal-body" style="font-size: 1.1em">
                    <form method="post" enctype="multipart/form-data" id="transasctionDoneForm">
                        @method('put')
                        @csrf
                        <input type="hidden" name="uuid" id="uuid" value="{{ old('uuid') }}">
                        <div class="mb-3">
                            <label for="proof_of_handover" class="form-label">Upload Gambar</label>
                            <input class="form-control @error('proof_of_handover') is-invalid @enderror" type="file" id="proof_of_handover" name="proof_of_handover" accept="image/*" required>
                            @error('proof_of_handover')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="text-end mt-4">
                            {{-- <div class="row"> --}}
                                {{-- <div class="col"> --}}
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" aria-label="Tutup">Tutup</button>
                                {{-- </div> --}}
                                {{-- <div class="col"> --}}
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                {{-- </div> --}}
                            {{-- </div> --}}
                        </div>
                    </form>
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

            $('#invoice_no').val(data.invoiceNo)
            $('#customer_name').val(data.customer);
            $('#outlet').val(data.outlet);
            $('#start_date').val(data.start);
            $('#end_date').val(data.end);
            $('#total_items').val(data.totalItems);
            $('#total_cost').val('IDR ' + data.totalCost.toLocaleString('en-US'));

            const paymentStatus = $('#payment_status')
            
            if (data.paymentStatus == 'paid') {
                paymentStatus.removeClass('bg-warning')
                paymentStatus.removeClass('bg-danger')
                paymentStatus.addClass('bg-success')
                paymentStatus.text('lunas')
            } else if (data.paymentStatus == 'unpaid') {
                paymentStatus.removeClass('bg-warning')
                paymentStatus.removeClass('bg-success')
                paymentStatus.addClass('bg-danger')
                paymentStatus.text('belum bayar')
            } else {
                paymentStatus.removeClass('bg-success')
                paymentStatus.removeClass('bg-danger')
                paymentStatus.addClass('bg-warning')
                paymentStatus.text('kurang bayar')
            }

            let details = data.transactionDetails;

            const capitalizeWord = (string) => {
                const firstChar = string.charAt(0).toUpperCase();
                const remainingChars = string.slice(1);
                return `${firstChar}${remainingChars}`;
            }

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
                $('#transasctionDoneForm').attr('action', `/dashboard/transaction/dropzone/${uuid}/status-update`);
                $('#transactionDoneModal').modal('show');
            }
        </script>
    @enderror

@endsection