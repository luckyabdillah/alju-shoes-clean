@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4">
            <h5 class="card-header">Laporan Transaksi</h5>
            <div class="card-body">
                <form class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3 col-6">
                            <select name="month" id="month" class="form-select">
                                <option value="01" {{ $month == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ $month == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ $month == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ $month == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ $month == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ $month == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ $month == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ $month == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <select name="year" id="year" class="form-select">
                                <option value="2024" {{ $year == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                                <option value="2027" {{ $year == '2027' ? 'selected' : '' }}>2027</option>
                                <option value="2028" {{ $year == '2028' ? 'selected' : '' }}>2028</option>
                                <option value="2029" {{ $year == '2029' ? 'selected' : '' }}>2029</option>
                                <option value="2030" {{ $year == '2030' ? 'selected' : '' }}>2030</option>
                                <option value="2031" {{ $year == '2031' ? 'selected' : '' }}>2031</option>
                                <option value="2032" {{ $year == '2032' ? 'selected' : '' }}>2032</option>
                                <option value="2033" {{ $year == '2033' ? 'selected' : '' }}>2033</option>
                                <option value="2034" {{ $year == '2034' ? 'selected' : '' }}>2034</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <select name="id" id="id" class="form-select">
                                    <option value="">Semua Outlet</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->uuid }}" {{ $id == $outlet->uuid ? 'selected' : '' }}>{{ $outlet->outlet_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="text-end mb-4">
                    <a href="/dashboard/report/export-excel?month={{ $month }}&year={{ $year }}&id={{ $id }}" target="_blank" class="btn btn-outline-secondary">Export Excel</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center data-table">
                        <thead>
                            <tr style="vertical-align: middle;">
                                <th class="text-center" style="width: 1px;">No</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">No HP</th>
                                <th class="text-center">Outlet</th>
                                <th class="text-center">Merk</th>
                                <th class="text-center">Treatment</th>
                                <th class="text-center">Harga / Laba Kotor</th>
                                <th class="text-center">Potongan Mitra</th>
                                <th class="text-center">Laba Setelah Potongan Mitra</th>
                                <th class="text-center">Metode Pembayaran</th>
                                <th class="text-center">Status Pembayaran</th>
                                <th class="text-center">Status Transaksi</th>
                                <th class="text-center">Ongkir</th>
                                <th class="text-center">Bukti Pengambilan</th>
                                <th class="text-center">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $totalCost = 0;
                                $totalParnterPrice = 0;
                                $totalProfit = 0;
                                $lastTransactionIdFromLooping = 0;
                            @endphp
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ date('d M Y, H:i:s', strtotime($transaction->created_at)) }}</td>
                                    <td>{{ $transaction->transaction->customer->name }}</td>
                                    <td>{{ $transaction->transaction->customer->whatsapp_number }}</td>
                                    <td>{{ $transaction->transaction->outlet->outlet_name }}</td>
                                    <td>{{ ucfirst($transaction->type) }} {{ $transaction->merk }}{{ $transaction->size ? ", $transaction->size" : '' }}</td>
                                    <td>{{ $transaction->treatment_name }}</td>
                                    <td>{{ number_format($transaction->cost, 0, ',', '.') }}</td>
                                    <td>{{ number_format($transaction->partner_price, 0, ',', '.') }}</td>
                                    <td>{{ number_format($transaction->cost - $transaction->partner_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($transaction->transaction->payment_method == 'cash')
                                            Cash
                                        @else
                                            <a href="{{ asset('storage') . '/' . $transaction->transaction->proof_of_payment }}" data-toggle="lightbox" style="text-decoration: underline;">QRIS</a>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->transaction->payment_status == 'paid' ? 'Lunas' : 'Belum' }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $transaction->transaction->transaction_status)) }}</td>
                                    <td>
                                        @if ($transaction->transaction->id != $lastTransactionIdFromLooping)
                                            {{ number_format($transaction->transaction->shipping_cost - $transaction->transaction->delivery_discount, 0, ',', '.') }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->transaction->transaction_status == 'done')
                                            <a href="{{ asset('storage') . '/' . $transaction->transaction->proof_of_handover }}" data-toggle="lightbox">Klik untuk melihat</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-danger" target="_blank" href="/invoice/{{ $transaction->transaction->uuid }}">
                                            Invoice
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                    $lastTransactionIdFromLooping = $transaction->transaction->id;
                                    $totalCost += $transaction->cost;
                                    $totalParnterPrice += $transaction->partner_price;
                                @endphp
                            @endforeach
                            <tr>
                                <td>{{ $i }}</td>
                                <td class="fw-bold">Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($totalCost, 0, ',', '.') }}</td>
                                <td>{{ number_format($totalParnterPrice, 0, ',', '.') }}</td>
                                <td>{{ number_format($totalCost - $totalParnterPrice, 0, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
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
                $(this.api().table().container()).find('input').attr('autocomplete', 'off')
            },
        })
    </script>
@endsection