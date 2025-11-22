@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Daftar Outlet</h4>
            <div class="card-body">
                <button class="btn btn-dark btn-add mb-3" data-bs-toggle="modal" data-bs-target="#outletModal">Tambah Outlet</button>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap text-center">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kode Outlet</th>
                                <th>Kode Order URL</th>
                                <th>Biaya Potongan Mitra</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($outlets->count())
                                @foreach ($outlets as $outlet)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $outlet->outlet_name }}</td>
                                        <td>{{ $outlet->outlet_code }}</td>
                                        <td class="text-wrap">
                                            @if ($outlet->id != 1)
                                                <a href="{{ url("/order?o=$outlet->uuid") }}" target="_blank">{{ url("/order?o=$outlet->uuid") }}</a>
                                            @endif
                                        </td>
                                        <td>IDR {{ number_format($outlet->partner_price, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <form action="/dashboard/master-data/outlet/{{ $outlet->uuid }}/status-update" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="border-0 btn-status bg-light">
                                                    <i class="bx bxs-circle text-{{ $outlet->status == 1 ? 'success' : 'danger' }} fs-4"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                class="btn btn-warning btn-edit me-2"
                                                data='{
                                                    "uuid":"{{ $outlet->uuid }}",
                                                    "outletName":"{{ $outlet->outlet_name }}",
                                                    "outletCode":"{{ $outlet->outlet_code }}",
                                                    "outletPrice":"{{ $outlet->partner_price }}"
                                                }'
                                            >
                                                <i class="bx bx-pencil me-1"></i> Edit
                                            </button>
                                            @if ($outlet->id != 1)
                                                <form action="/dashboard/master-data/outlet/{{ $outlet->uuid }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="6">No data outlet found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Outlet Modal -->
    <div class="modal fade" id="outletModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="outletModalLabel"></h1>
                </div>
                <form method="post" id="modalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="outlet_name" class="form-label">Nama Outlet</label>
                            <input class="form-control @error('outlet_name') is-invalid @enderror" type="text" id="outlet_name" name="outlet_name" placeholder="Nama Outlet" autocomplete="off" value="{{ old('outlet_name') }}" required>
                            @error('outlet_name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="outlet_code" class="form-label">Kode Outlet</label>
                            <input class="form-control @error('outlet_code') is-invalid @enderror" type="text" id="outlet_code" name="outlet_code" placeholder="Kode Outlet" autocomplete="off" value="{{ old('outlet_code') }}" required>
                            @error('outlet_code')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="partner_price" class="form-label">Biaya Potongan Mitra</label>
                            <input class="form-control @error('partner_price') is-invalid @enderror" type="number" id="partner_price" name="partner_price" placeholder="Biaya Potongan Mitra" autocomplete="off" value="{{ old('partner_price') }}" required>
                            @error('partner_price')
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
            $('#modalForm').attr('action', '/dashboard/master-data/outlet');
            $('.modal-title').text('Tambah Outlet');

            const nameField = $('[name="outlet_name"]');
            $('[name="outlet_code"]').val('');
            $('[name="partner_price"]').val(0);
            nameField.val('');
            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $('#modalForm').prepend('@method("put")');
            $('.modal-title').text('Edit Outlet');
            let data = JSON.parse($(this).attr('data'));

            $('#modalForm').attr('action', '/dashboard/master-data/outlet/' + data.uuid)
            const nameField = $('[name="outlet_name"]');
            nameField.val(data.outletName);
            $('[name="outlet_code"]').val(data.outletCode);
            $('[name="partner_price"]').val(data.outletPrice);

            $('#outletModal').modal('show');

            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-cancel', function(e) {
            e.preventDefault();
            const modalForm = document.querySelector('#modalForm');
            modalForm.querySelector('input[name="_method"]').remove();
        });
    </script>
@endsection