@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Edit Gallery</h5>
                <div class="card-body">
                    @if ($gallery->img)
                        <img src="{{ asset('storage/' . $gallery->img) }}" alt="{{ $gallery->alt }}" class="img-thumbnail mb-3" width="200px">
                    @endif
                    <form action="/dashboard/master-data/gallery/{{ $gallery->uuid }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Gallery's name" autocomplete="off" value="{{ old('name', $gallery->name) }}">
                            @error('name')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="img" class="form-label">Image</label>
                            <input class="form-control @error('img') is-invalid @enderror" type="file" id="img" name="img">
                            @error('img')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="2" name="description">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Submit</button>
                        <a href="/dashboard/master-data/gallery" class="btn btn-secondary ms-1">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection