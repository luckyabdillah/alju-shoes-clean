@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Daftar Customer</h4>
            <div class="card-body">
                {{-- <h6><a href="/dashboard/master-data/outlet/create" class="btn btn-dark">Add New Outlet</a></h6> --}}
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Nomor WhatsApp</th>
                                <th class="text-center">Terakhir Order</th>
                                <th class="text-center">Jumlah Order</th>
                                <th class="text-center">Total Item</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $customer->name }}</td>
                                    <td class="text-center"><a href="https://wa.me/{{ $customer->whatsapp_number }}" target="_blank">{{ $customer->whatsapp_number }}</a></td>
                                    <td class="text-center">{{ $customer->last_order ? date('d M Y, H:i:s', strtotime($customer->last_order)) : '-' }}</td>
                                    {{-- <td class="text-center">{{ $customer->transactions->count() ?? 0 }}</td> --}}
                                    <td class="text-center">{{ $customer->total_order }}</td>
                                    <td class="text-center">{{ $customer->total_items }}</td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="me-2 btn btn-secondary btn-customer-detail badge my-badge"
                                            data='{
                                                "name":"{{ $customer->name }}",
                                                "number":"{{ $customer->whatsapp_number }}",
                                                "address":"{{ $customer->address }}",
                                                "last_order":"{{ $customer->last_order ? date('d M Y, H:i:s', strtotime($customer->last_order)) : '-' }}",
                                                "location":"{{ $customer->lat }},{{ $customer->long }}"
                                            }'
                                            >
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="customerDetailModalLabel">Detail Customer</h2>
                    </div>
                    <div class="modal-body" style="font-size: 1.1em">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Customer</label>
                            <input type="text" class="form-control" name="name" id="name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control" name="number" id="number" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" name="address" id="address" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="last_order" class="form-label">Terakhir Order</label>
                            <input type="text" class="form-control" name="last_order" id="last_order" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a target="_blank" class="btn btn-danger" id="location"><i class="bx bx-map"></i></a>
                        <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#data-table').DataTable({
            autoWidth: false,
            initComplete: function() {
                $(this.api().table().container()).find('input').attr('autocomplete', 'off');
            },
        });
    </script>
@endsection