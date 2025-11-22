@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Daftar Gallery</h4>
            <div class="card-body">
                {{-- <h6><a href="/dashboard/master-data/gallery/create" class="btn btn-dark">Add New Gallery</a></h6> --}}
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic text-center" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($galleries->count())
                                @foreach ($galleries as $gallery)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $gallery->img) }}" data-toggle="lightbox" data-gallery="mini-gallery">
                                            <img src="{{ asset('storage/' . $gallery->img) }}" alt="{{ $gallery->alt }}" class="img-thumbnail d-block mx-auto" width="150px">
                                        </a>
                                    </td>
                                    <td>{{ $gallery->name }}</td>
                                    <td>{{ $gallery->description }}</td>
                                    <td>
                                        <a class="me-2 btn btn-secondary" href="/dashboard/master-data/gallery/{{ $gallery->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a>
                                        <form action="/dashboard/master-data/gallery/{{ $gallery->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                
                            @else
                                <tr>
                                    <td colspan="6">No data gallery found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection