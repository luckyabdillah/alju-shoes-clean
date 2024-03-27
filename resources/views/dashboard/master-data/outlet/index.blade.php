@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Outlet List</h4>
            <div class="card-body">
                <button class="btn btn-dark btn-add mb-3" data-bs-toggle="modal" data-bs-target="#outletModal">Add New Outlet</button>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Outlet Code</th>
                                <th>URL Order Page</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
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
                                        <a href="http://localhost:8000/order?o={{ $outlet->uuid }}" target="_blank">http://localhost:8000/order?o={{ $outlet->uuid }}</a>
                                    </td>
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
                                        {{-- <a class="me-2 btn btn-secondary" href="/dashboard/master-data/outlet/{{ $outlet->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a> --}}
                                        <button
                                        class="btn btn-warning btn-edit me-2"
                                        data='{
                                            "uuid":"{{ $outlet->uuid }}",
                                            "outletName":"{{ $outlet->outlet_name }}",
                                            "outletCode":"{{ $outlet->outlet_code }}"
                                        }'
                                        >
                                        <i class="bx bx-pencil me-1"></i> Edit
                                    </button>
                                        <form action="/dashboard/master-data/outlet/{{ $outlet->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>
                                        </form>
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
                            <label for="outlet_name" class="form-label">Outlet Name</label>
                            <input class="form-control @error('outlet_name') is-invalid @enderror" type="text" id="outlet_name" name="outlet_name" placeholder="Outlet's name" autocomplete="off" value="{{ old('outlet_name') }}">
                            @error('outlet_name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="outlet_code" class="form-label">Outlet Code</label>
                            <input class="form-control @error('outlet_code') is-invalid @enderror" type="text" id="outlet_code" name="outlet_code" placeholder="Outlet's code" autocomplete="off" value="{{ old('outlet_code') }}">
                            @error('outlet_code')
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
            $('.modal-title').text('Add New Outlet');

            const nameField = $('[name="outlet_name"]');
            $('[name="outlet_code"]').val('');
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

            $('#modalForm').attr('action', '/dashboard/master-data/treatment/' + data.uuid)
            const nameField = $('[name="outlet_name"]');
            nameField.val(data.outletName);
            $('[name="outlet_code"]').val(data.outletCode);

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