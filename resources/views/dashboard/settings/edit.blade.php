@extends('dashboard.layouts.main')

@section('content')
    @if (session()->has('success'))
    <div class="flash-data" data-flash="{{ session('success') }}"></div>
    @endif
    @if (session()->has('failed'))
    <div class="flash-data-failed" data-flash="{{ session('failed') }}"></div>
    @endif
    <div class="container mt-4">
        <div class="card mb-3">
            <h4 class="card-header">Settings</h4>
            <div class="card-body">
                <form action="/dashboard/settings" method="POST">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Nama" autocomplete="off" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" placeholder="Username" autocomplete="off" value="{{ old('username', auth()->user()->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger mt-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="card">
            <h4 class="card-header">Ganti Password</h4>
            <div class="card-body">
                <form action="/dashboard/change-password" method="POST">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Password Lama</label>
                        <input class="form-control @error('old_password') is-invalid @enderror" type="password" id="old_password" name="old_password" placeholder="Password Lama" autocomplete="off" required>
                        @error('old_password')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input class="form-control @error('new_password') is-invalid @enderror" type="password" id="new_password" name="new_password" placeholder="Password Baru" autocomplete="off" required>
                        @error('new_password')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input class="form-control @error('new_password_confirmation') is-invalid @enderror" type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Konfirmasi Password" autocomplete="off" required>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection