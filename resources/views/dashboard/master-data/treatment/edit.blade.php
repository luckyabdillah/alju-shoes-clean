@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Edit Treatment</h5>
                <div class="card-body">
                    <form action="/dashboard/master-data/treatment/{{ $treatment->uuid }}" method="post">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Treatment's name" autocomplete="off" value="{{ old('name', $treatment->name) }}">
                            @error('name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <input class="form-control @error('cost') is-invalid @enderror" type="number" id="cost" name="cost" placeholder="50000" value="{{ old('cost', $treatment->cost) }}">
                            @error('cost')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="processing_time" class="form-label">Processing time</label>
                            <input class="form-control @error('processing_time') is-invalid @enderror" type="number" id="processing_time" name="processing_time" placeholder="2" value="{{ old('processing_time', $treatment->processing_time) }}">
                            @error('processing_time')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Submit</button>
                        <a href="/dashboard/master-data/treatment" class="btn btn-secondary ms-1">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection