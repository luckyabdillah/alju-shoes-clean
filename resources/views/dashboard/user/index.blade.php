@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Daftar User</h4>
            <div class="card-body">
                <a href="/dashboard/user/create" class="btn btn-dark mb-3">Tambah User</a>
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-basic" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Outlet</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->username }}</td>
                                    <td class="text-center">{{ ucwords($user->role) }}</td>
                                    <td class="text-center">{{ $user->outlet_id ? $user->outlet->outlet_name : '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-dark btn-reset-password me-2" data-uuid="{{ $user->uuid }}">
                                            <i class="bx bx-key"></i> Reset Password
                                        </button>
                                        <a href="/dashboard/user/{{ $user->uuid }}/edit" class="btn btn-warning btn-edit me-2">
                                            <i class="bx bx-pencil me-1"></i> Edit
                                        </a>
                                        <form action="/dashboard/user/{{ $user->uuid }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-delete" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="resetPasswordModalLabel"></h1>
                </div>
                <form method="post" id="modalForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" autocomplete="off" value="{{ old('password') }}" required>
                            @error('password')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="off" value="{{ old('password_confirmation') }}" required>
                            @error('password_confirmation')
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
        $('#data-table').DataTable({
            autoWidth: false,
            initComplete: function() {
                $(this.api().table().container()).find('input').attr('autocomplete', 'off');
            },
        });

        $(document).on('click', '.btn-reset-password', function(e) {
            e.preventDefault();
            $('.modal-title').text('Reset Password');
            let uuid = $(this).attr('data-uuid');

            $('#modalForm').attr('action', `/dashboard/user/${uuid}/reset-password`)
            const passwordField = $('[password="password"]');
            passwordField.val('');
            $('[name="password_confirmation"]').val('');

            $('#resetPasswordModal').modal('show');

            setTimeout(function() { 
                nameField.focus(); 
            }, 400);
        });
    </script>
@endsection