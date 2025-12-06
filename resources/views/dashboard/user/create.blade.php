@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Tambah User</h4>
            <div class="card-body">
                <form action="/dashboard/user" method="POST">
                    @csrf
                    <a href="/dashboard/user" class="btn btn-dark mb-3">Kembali</a>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Nama" autocomplete="off" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" placeholder="Username" autocomplete="off" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                                <option value="operation" {{ old('role') == 'operation' ? 'selected' : '' }}>Operation</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="outlet_id" class="form-label">Outlet</label>
                            <select class="form-select @error('outlet_id') is-invalid @enderror" id="outlet_id" name="outlet_id">
                                <option value="">-</option>
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->outlet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" autocomplete="off" value="{{ old('password') }}" required>
                            @error('password')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="off" value="{{ old('password_confirmation') }}" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection