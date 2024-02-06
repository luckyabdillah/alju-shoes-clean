@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Outlet List</h4>
            <div class="card-body">
                <h6><a href="/dashboard/master-data/outlet/create" class="btn btn-dark">Add New Outlet</a></h6>
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
                                        <a class="me-2 btn btn-secondary" href="/dashboard/master-data/outlet/{{ $outlet->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a>
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
@endsection