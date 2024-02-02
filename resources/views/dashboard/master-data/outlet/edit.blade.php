@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Edit Outlet</h5>
                <div class="card-body">
                    <form action="/dashboard/master-data/outlet/{{ $outlet->uuid }}" method="post">
                        @method('put')
                        @csrf
                        <div class="mb-3">
                            <label for="outlet_name" class="form-label">Outlet Name</label>
                            <input class="form-control @error('outlet_name') is-invalid @enderror" type="text" id="outlet_name" name="outlet_name" placeholder="Outlet's name" autocomplete="off" value="{{ old('outlet_name', $outlet->outlet_name) }}">
                            @error('outlet_name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="outlet_code" class="form-label">Outlet Code</label>
                            <input class="form-control @error('outlet_code') is-invalid @enderror" type="text" id="outlet_code" name="outlet_code" placeholder="Outlet's code" autocomplete="off" value="{{ old('outlet_code', $outlet->outlet_code) }}">
                            @error('outlet_code')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Submit</button>
                        <a href="/dashboard/master-data/outlet" class="btn btn-secondary ms-1">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection