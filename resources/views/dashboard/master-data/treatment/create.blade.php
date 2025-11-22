@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Tambah Treatment Grouping</h5>
                <div class="card-body">
                    <form action="/dashboard/master-data/treatment" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Deep Cleaning" value="{{ old('name') }}" required autocomplete="off"/>
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
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Icon</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" required accept="image/*"/>
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