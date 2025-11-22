@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Edit Transaksi</h4>
            <div class="card-body">
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <form method="get">
                    <div class="row g-3 mb-4">
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
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Outlet</th>
                                <th class="text-center">Pembayaran</th>
                                <th class="text-center">Tagihan</th>
                                <th class="text-center">Status Transaksi</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
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
                                    <td>IDR {{ number_format($transaction->total_cost, 0, '.', ',') }} | {!! $transaction->payment_status == 'paid' ? '<span class="text-success">Lunas</span>' : '<span class="text-danger">Belum</span>' !!}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $transaction->transaction_status)) }}</td>
                                    <td>
                                        <a href="/dashboard/transaction/{{ $transaction->uuid }}/edit?month={{ $month }}&year={{ $year }}&id={{ $id }}" class="btn btn-secondary badge my-badge">
                                            <i class="bx bx-show-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
                $(this.api().table().container()).find('input').attr('autocomplete', 'off');
            },
        });
    </script>
@endsection