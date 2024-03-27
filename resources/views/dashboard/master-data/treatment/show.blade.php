@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Treatment List : {{ $treatment->name }}</h4>
            <div class="card-body">
                <a href="/dashboard/master-data/treatment" class="d-block mb-3">← Back</a>
                {{-- <button class="btn btn-secondary me-1 mb-3">Back</button> --}}
                <button class="btn btn-dark btn-add mb-3" data-bs-toggle="modal" data-bs-target="#treatmentModal">Add New Treatment</button>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th class="text-start">Name</th>
                                <th>Cost</th>
                                <th>Processing Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($items->count())
                                @foreach ($items as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->name }}</td>
                                    <td>IDR {{ number_format($item->cost, 0, '.', ',') }}</td>
                                    <td>{{ $item->processing_time }} days</td>
                                    <td class="text-center">
                                        {{-- <a class="me-2 btn btn-secondary" href="/dashboard/master-data/treatment/{{ $item->uuid }}/edit"><i class="bx bx-pencil me-1"></i> Edit</a> --}}
                                        <button
                                        class="btn btn-warning btn-edit me-2"
                                        data='{
                                            "uuid":"{{ $item->uuid }}",
                                            "name":"{{ $item->name }}",
                                            "cost":"{{ $item->cost }}",
                                            "processingTime":"{{ $item->processing_time }}"
                                        }'
                                        >
                                        <i class="bx bx-pencil me-1"></i> Edit
                                    </button>
                                        <form action="/dashboard/master-data/treatment/{{ $treatment->uuid }}/{{ $item->uuid }}" method="post" class="d-inline">
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

    <!-- Treatment Modal -->
    <div class="modal fade" id="treatmentModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="treatmentModalLabel"></h1>
                </div>
                <form method="post" id="modalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Reguler Mild" value="{{ old('name') }}" required autocomplete="off"/>
                            @error('name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <input class="form-control @error('cost') is-invalid @enderror" type="number" id="cost" name="cost" placeholder="50000" value="{{ old('cost') }}" required autocomplete="off">
                            @error('cost')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="processing_time" class="form-label">Processing time</label>
                            <input class="form-control @error('processing_time') is-invalid @enderror" type="number" id="processing_time" name="processing_time" placeholder="2" value="{{ old('processing_time') }}" required autocomplete="off">
                            @error('processing_time')
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
            $('#modalForm').attr('action', '/dashboard/master-data/treatment/{{ $treatment->uuid }}');
            $('.modal-title').text('Add New Treatment');

            const nameField = $('[name="name"]');
            nameField.val('');
            $('[name="cost"]').val('');
            $('[name="processing_time"]').val('');
            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $('#modalForm').prepend('@method("put")');
            $('.modal-title').text('Edit Treatment');
            let data = JSON.parse($(this).attr('data'));

            $('#modalForm').attr('action', '/dashboard/master-data/treatment/{{ $treatment->uuid }}/' + data.uuid)
            const nameField = $('[name="name"]');
            nameField.val(data.name);
            $('[name="cost"]').val(data.cost);
            $('[name="processing_time"]').val(data.processingTime);

            $('#treatmentModal').modal('show');

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