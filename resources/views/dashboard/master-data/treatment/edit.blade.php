@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Edit Treatment Grouping</h5>
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset("storage/$treatment->photo") }}" alt="Treatment Icon" width="75">
                    </div>
                    <form action="/dashboard/master-data/treatment/{{ $treatment->uuid }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Deep Cleaning" value="{{ old('name', $treatment->name) }}" required autocomplete="off"/>
                            @error('name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Tipe</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="sepatu" {{ old('type', $treatment->type) == 'sepatu' ? 'selected' : '' }}>Sepatu</option>
                                    <option value="tas" {{ old('type', $treatment->type) == 'tas' ? 'selected' : '' }}>Tas</option>
                                    <option value="sandal" {{ old('type', $treatment->type) == 'sandal' ? 'selected' : '' }}>Sandal</option>
                                    <option value="topi" {{ old('type', $treatment->type) == 'topi' ? 'selected' : '' }}>Topi</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Icon</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*"/>
                                @error('photo')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark">Submit</button>
                            <a href="/dashboard/master-data/treatment" class="btn btn-secondary ms-1">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection