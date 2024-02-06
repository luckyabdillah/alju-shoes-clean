@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Campaign List</h4>
            <div class="card-body">
                <h6><a href="/dashboard/master-data/campaign/create" class="btn btn-dark">Add New Campaign</a></h6>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($campaigns->count())
                                @foreach ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $campaign->img) }}" data-toggle="lightbox" data-gallery="mini-gallery">
                                            <img src="{{ asset('storage/' . $campaign->img) }}" alt="{{ $campaign->alt }}" class="img-thumbnail d-block mx-auto" width="150px">
                                        </a>
                                    </td>
                                    <td>{{ $campaign->name }}</td>
                                    <td>{{ $campaign->description }}</td>
                                    <td class="text-center">
                                        <form action="/dashboard/master-data/campaign/{{ $campaign->uuid }}/status-update" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="border-0 btn-status bg-light">
                                                <i class="bx bxs-circle text-{{ $campaign->status == 1 ? 'success' : 'danger' }} fs-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <a class="me-2 btn btn-secondary" href="/dashboard/master-data/campaign/{{ $campaign->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a>
                                        <form action="/dashboard/master-data/campaign/{{ $campaign->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="6">No data campaign found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection