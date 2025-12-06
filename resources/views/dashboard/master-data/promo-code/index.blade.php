@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Daftar Kode Promo</h4>
            <div class="card-body">
                <button class="btn btn-dark btn-add mb-3" data-bs-toggle="modal" data-bs-target="#promoCodeModal">Tambah Kode Promo</button>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap text-center">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Potongan</th>
                                <th>Tipe Promo</th>
                                <th>Tanggal Kedaluwarsa</th>
                                <th>Min Belanja</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($promoCodes->count())
                                @foreach ($promoCodes as $code)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $code->code }}</td>
                                    <td>
                                        @if ($code->type == 'percentage')
                                            {{ number_format($code->amount, 0, ',', '.') }}%
                                        @else
                                            IDR {{ number_format($code->amount, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td>{{ $code->promo_type == 'treatment' ? 'Treatment' : 'Delivery' }}</td>
                                    <td>{{ date('d M Y', strtotime($code->expiration_date)) }}</td>
                                    <td>IDR {{ number_format($code->min_spend, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-warning btn-edit me-2"
                                            data='{
                                                "id":"{{ $code->id }}",
                                                "code":"{{ $code->code }}",
                                                "amount":{{ $code->amount }},
                                                "type":"{{ $code->type }}",
                                                "promoType":"{{ $code->promo_type }}",
                                                "expirationDate":"{{ $code->expiration_date }}",
                                                "minSpend":{{ $code->min_spend }}
                                            }'
                                        >
                                        <i class="bx bx-pencil me-1"></i> Edit
                                    </button>
                                        <form action="/dashboard/master-data/promo-code/{{ $code->id }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="7">No data promo code found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Code Modal -->
    <div class="modal fade" id="promoCodeModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="promoCodeModalLabel"></h1>
                </div>
                <form method="post" id="modalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="code" class="form-label">Kode</label>
                            <input class="form-control @error('code') is-invalid @enderror" type="text" id="code" name="code" placeholder="Kode" autocomplete="off" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Potongan</label>
                                <input class="form-control @error('amount') is-invalid @enderror" type="number" min="0" step="1" id="amount" name="amount" placeholder="Potongan" autocomplete="off" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label">Tipe</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option value="amount">Nominal</option>
                                    <option value="percentage">Persentase</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="promo_type" class="form-label">Tipe Promo</label>
                                <select class="form-select @error('promo_type') is-invalid @enderror" id="promo_type" name="promo_type">
                                    <option value="treatment">Treatment</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                                @error('promo_type')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="min_spend" class="form-label">Minimum Belanja</label>
                                <input class="form-control @error('min_spend') is-invalid @enderror" type="number" min="0" step="1" id="min_spend" name="min_spend" placeholder="Minimum Belanja" autocomplete="off" value="{{ old('min_spend') }}" required>
                                @error('min_spend')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="expiration_date" class="form-label">Tanggal Kedaluwarsa</label>
                            <input class="form-control @error('expiration_date') is-invalid @enderror" type="date" id="expiration_date" name="expiration_date" placeholder="Tanggal Kedaluwarsa" autocomplete="off" value="{{ old('expiration_date') }}" required>
                            @error('expiration_date')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.btn-add', function(e) {
            e.preventDefault();
            $('#modalForm').attr('action', '/dashboard/master-data/promo-code');
            $('.modal-title').text('Tambah Kode Promo');

            const codeField = $('[name="code"]');
            $('[name="amount"]').val(0);
            $('[name="min_spend"]').val(0);
            $('[name="expiration_date"]').val('');
            codeField.val('');
            setTimeout(function() { 
                codeField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $('#modalForm').prepend('@method("put")');
            $('.modal-title').text('Edit Kode Promo');
            let data = JSON.parse($(this).attr('data'));

            $('#modalForm').attr('action', '/dashboard/master-data/promo-code/' + data.id)
            const codeField = $('[name="code"]');
            codeField.val(data.code);
            $('[name="amount"]').val(data.amount);
            $('[name="type"]').val(data.type);
            $('[name="promo_type"]').val(data.promoType);
            $('[name="min_spend"]').val(data.minSpend);
            $('[name="expiration_date"]').val(data.expirationDate);

            $('#promoCodeModal').modal('show');

            setTimeout(function() { 
                codeField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-cancel', function(e) {
            e.preventDefault();
            const modalForm = document.querySelector('#modalForm');
            modalForm.querySelector('input[name="_method"]').remove();
        });
    </script>
@endsection