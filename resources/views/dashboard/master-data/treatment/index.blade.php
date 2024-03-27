@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Treatment Grouping</h4>
            <div class="card-body">
                {{-- <h6><a href="/dashboard/master-data/treatment/create" class="btn btn-dark">Add New Grouping</a></h6> --}}
                <button class="btn btn-dark btn-add mb-3" data-bs-toggle="modal" data-bs-target="#treatmentGroupingModal">Add New Grouping</button>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th class="text-start">Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($treatments->count())
                                @foreach ($treatments as $treatment)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $treatment->name }}</td>
                                    <td class="text-center">
                                        <a class="me-2 btn btn-secondary" href="/dashboard/master-data/treatment/{{ $treatment->uuid }}"><i class="bx bx-list-ul me-1"></i> List</a>
                                        {{-- <a class="me-2 btn btn-warning" href="/dashboard/master-data/treatment/{{ $treatment->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a> --}}
                                        <button
                                            class="btn btn-warning btn-edit me-2"
                                            data='{
                                                "uuid":"{{ $treatment->uuid }}",
                                                "name":"{{ $treatment->name }}"
                                            }'
                                            >
                                            <i class="bx bx-pencil me-1"></i> Edit
                                        </button>
                                        <form action="/dashboard/master-data/treatment/{{ $treatment->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="6">No data treatment found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Grouping Modal -->
    <div class="modal fade" id="treatmentGroupingModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="treatmentGroupingModalLabel"></h1>
                </div>
                <form method="post" id="modalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Deep Cleaning" value="{{ old('name') }}" required autocomplete="off"/>
                            @error('name')
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
            $('#modalForm').attr('action', '/dashboard/master-data/treatment');
            $('.modal-title').text('Add New Treatment Grouping');

            const nameField = $('[name="name"]');
            nameField.val('');
            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $('#modalForm').prepend('@method("put")');
            $('.modal-title').text('Edit Treatment Grouping');
            let data = JSON.parse($(this).attr('data'));

            $('#modalForm').attr('action', '/dashboard/master-data/treatment/' + data.uuid)
            const nameField = $('[name="name"]');
            nameField.val(data.name);

            $('#treatmentGroupingModal').modal('show');

            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-cancel', function(e) {
            e.preventDefault();
            $('input[name="_method"]').remove();
        });
    </script>
@endsection