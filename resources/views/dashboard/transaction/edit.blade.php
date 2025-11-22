@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Edit Transaksi</h4>
            <div class="card-body">
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <a href="/dashboard/transaction?month={{ $month }}&year={{ $year }}&id={{ $id }}" class="btn btn-dark mb-3">Kembali</a>
                <form action="/dashboard/transaction/{{ $transaction->uuid }}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-check mb-2">
                        <input class="form-check-input checkbox-primary" type="checkbox" value="paid" id="payment_status" name="payment_status" {{ $transaction->payment_status == 'paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="payment_status">
                            Lunas
                        </label>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ $transaction->invoice_no }}" disabled>
                        </div>
                        <div class="col-md-4 col-6">
                            <label for="transaction_type" class="form-label">Tipe Transaksi</label>
                            <input type="text" name="transaction_type" id="transaction_type" class="form-control" value="{{ ucwords(str_replace('-', ' ', $transaction->transaction_type)) }}" disabled>
                        </div>
                        <div class="col-md-4 col-6">
                            <label for="total_cost" class="form-label">Total Tagihan</label>
                            <input type="text" name="total_cost" id="total_cost" class="form-control" value="IDR {{ number_format($transaction->total_cost, 0, ',', '.') }}" disabled>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="shipping_cost" class="form-label">Biaya Pickup & Delivery</label>
                            <input type="number" min="0" name="shipping_cost" id="shipping_cost" placeholder="Biaya Pickup & Delivery" class="form-control @error('shipping_cost') is-invalid @enderror" value="{{ $transaction->shipping_cost }}" required>
                            @error('shipping_cost')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="treatment_discount" class="form-label">Diskon Treatment</label>
                            <input type="number" min="0" name="treatment_discount" id="treatment_discount" placeholder="Diskon Treatment" class="form-control @error('treatment_discount') is-invalid @enderror" value="{{ $transaction->treatment_discount }}" required>
                            @error('treatment_discount')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="delivery_discount" class="form-label">Diskon Delivery</label>
                            <input type="number" min="0" name="delivery_discount" id="delivery_discount" placeholder="Diskon Delivery" class="form-control @error('delivery_discount') is-invalid @enderror" value="{{ $transaction->delivery_discount }}" required>
                            @error('delivery_discount')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="transaction_end" class="form-label">Tanggal Selesai</label>
                            <input type="datetime-local" name="transaction_end" id="transaction_end" class="form-control @error('transaction_end') is-invalid @enderror" value="{{ date('Y-m-d H:i:s', strtotime($transaction->transaction_end)) }}" required>
                            @error('transaction_end')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Merk</th>
                                    <th class="text-center">Treatment</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Biaya Potongan Mitra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                    $totalPartnerPrice = 0;
                                @endphp
                                @foreach ($details as $detail)
                                    <input type="hidden" name="details[{{ $loop->iteration }}][id]" value="{{ $detail->id }}">
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($detail->type) }}</td>
                                        <td>{{ $detail->merk }}{{ $detail->size ? ", $detail->size" : '' }}</td>
                                        <td style="min-width: 250px;">
                                            <input type="text" class="form-control" name="details[{{ $loop->iteration }}][treatment_name]" id="details.{{ $loop->iteration }}.treatment_name" value="{{ $detail->treatment_name }}">
                                        </td>
                                        <td style="min-width: 150px; width: 150px;">
                                            <input type="number" min="0" class="form-control text-center" name="details[{{ $loop->iteration }}][cost]" id="details.{{ $loop->iteration }}.cost" value="{{ $detail->cost }}">
                                        </td>
                                        <td style="min-width: 150px; width: 150px;">
                                            <input type="number" min="0" class="form-control text-center" name="details[{{ $loop->iteration }}][partner_price]" id="details.{{ $loop->iteration }}.partner_price" value="{{ $detail->partner_price }}">
                                        </td>
                                    </tr>
                                    @php
                                        $subtotal += $detail->cost;
                                        $totalPartnerPrice += $detail->partner_price;
                                    @endphp
                                @endforeach
                                <tr class="text-center">
                                    <td colspan="4" class="text-start fw-semibold">Total</td>
                                    <td>{{ number_format($subtotal, 0, '.', ',') }}</td>
                                    <td>{{ number_format($totalPartnerPrice, 0, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-danger">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection