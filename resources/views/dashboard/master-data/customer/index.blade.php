@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Customer List</h4>
            <div class="card-body">
                {{-- <h6><a href="/dashboard/master-data/outlet/create" class="btn btn-dark">Add New Outlet</a></h6> --}}
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>WhatsApp Number</th>
                                <th class="text-center">Last Order</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customers->count())
                                @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td><a href="https://wa.me/{{ $customer->whatsapp_number }}" target="_blank">{{ $customer->whatsapp_number }}</a></td>
                                    <td class="text-center">{{ date_format(date_create($customer->last_order), 'd M Y, H:i:s') }}</td>
                                    {{-- <td class="text-wrap">
                                        <a href="http://localhost:8000/order?o={{ $outlet->uuid }}" target="_blank">http://localhost:8000/order?o={{ $outlet->uuid }}</a>
                                    </td> --}}
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="me-2 btn btn-secondary btn-customer-detail"
                                            data='{
                                                "name":"{{ $customer->name }}",
                                                "number":"{{ $customer->whatsapp_number }}",
                                                "address":"{{ $customer->address }}",
                                                "last_order":"{{ date_format(date_create($customer->last_order), 'd M Y, H:i:s') }}",
                                                "location":"https://www.google.com/maps/place/{{ $customer->lat }},{{ $customer->long }}"
                                            }'
                                            >
                                            <i class="bx bx-show me-1"></i> Detail
                                        </button>
                                        {{-- <form action="/dashboard/master-data/customer/{{ $customer->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>
                                        </form> --}}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="6">No data customer found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="customerDetailModalLabel">Customer Detail</h2>
                    </div>
                    <div class="modal-body" style="font-size: 1.1em">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <b>Customer Name </b>: <span id="name"></span>
                            </li>
                            <li class="list-group-item">
                                <b>WhtasApp Number </b>: <span id="number"></span>
                            </li>
                            <li class="list-group-item">
                                <b>Address </b>: <span id="address"></span>
                            </li>
                            <li class="list-group-item">
                                <b>Last Order </b>: <span id="last_order"></span>
                            </li>
                            <li class="list-group-item">
                                <b>Location </b>: <span id="location"></span>
                            </li>
                        </ul>
                        <div class="text-end">
                            <button class="btn btn-secondary mt-3" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection