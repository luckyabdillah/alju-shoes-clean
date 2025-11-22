@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Treatment Grouping</h4>
            <div class="card-body">
                <a href="/dashboard/master-data/treatment/create" class="btn btn-dark mb-3">Tambah Grouping</a>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Icon</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($treatments->count())
                                @foreach ($treatments as $treatment)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $treatment->name }}</td>
                                    <td>
                                        <img src="{{ asset("storage/$treatment->photo") }}" alt="Treatment Icon" width="50">    
                                    </td>
                                    <td class="text-center">
                                        <a class="me-2 btn btn-secondary" href="/dashboard/master-data/treatment/{{ $treatment->uuid }}"><i class="bx bx-list-ul me-1"></i> List</a>
                                        <a href="/dashboard/master-data/treatment/{{ $treatment->uuid }}/edit" class="btn btn-warning me-2">
                                            <i class="bx bx-pencil me-1"></i> Edit
                                        </a>
                                        <form action="/dashboard/master-data/treatment/{{ $treatment->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Hapus</button>
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
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="sepatu">Sepatu</option>
                                <option value="tas">Tas</option>
                                <option value="sandal">Sandal</option>
                                <option value="topi">Topi</option>
                            </select>
                            @error('type')
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
            $('[name="type"]').val('sepatu');
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
            $('[name="type"]').val(data.type);

            $('#treatmentGroupingModal').modal('show');

            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-cancel', function(e) {
            e.preventDefault();
            $('input[name="_method"][value="put"]').remove();
        });
    </script>
@endsection