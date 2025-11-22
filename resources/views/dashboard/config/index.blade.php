@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <h4 class="card-header">Config</h4>
            <div class="card-body">
                @if (session()->has('success'))
                <div class="flash-data" data-flash="{{ session('success') }}"></div>
                @endif
                <form action="/dashboard/config" method="post">
                    @csrf
                    @method('put')
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="hari_libur" class="form-label">Hari Libur</label>
                            <select name="hari_libur" id="hari_libur" class="form-select">
                                <option value="Monday" {{ old('hari_libur', $hariLibur->value) == 'Monday' ? 'selected' : '' }}>Senin</option>
                                <option value="Tuesday" {{ old('hari_libur', $hariLibur->value) == 'Tuesday' ? 'selected' : '' }}>Selasa</option>
                                <option value="Wednesday" {{ old('hari_libur', $hariLibur->value) == 'Wednesday' ? 'selected' : '' }}>Rabu</option>
                                <option value="Thursday" {{ old('hari_libur', $hariLibur->value) == 'Thursday' ? 'selected' : '' }}>Kamis</option>
                                <option value="Friday" {{ old('hari_libur', $hariLibur->value) == 'Friday' ? 'selected' : '' }}>Jumat</option>
                                <option value="Saturday" {{ old('hari_libur', $hariLibur->value) == 'Saturday' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Sunday" {{ old('hari_libur', $hariLibur->value) == 'Sunday' ? 'selected' : '' }}>Minggu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="biaya_pengantaran" class="form-label">Biaya Pengantaran (Per KM)</label>
                            <input type="number" name="biaya_pengantaran" id="biaya_pengantaran" class="form-control @error('biaya_pengantaran') is-invalid @enderror" min="0" step="1" required autocomplete="off" placeholder="Biaya Pengantaran (Per KM)" value="{{ old('biaya_pengantaran', $biayaPengantaran->value) }}">
                            @error('biaya_pengantaran')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="gratis_pengantaran" class="form-label">Gratis Pengantaran (KM)</label>
                            <input type="number" name="gratis_pengantaran" id="gratis_pengantaran" class="form-control @error('gratis_pengantaran') is-invalid @enderror" min="0" step="1" required autocomplete="off" placeholder="Gratis Pengantaran (Meter)" value="{{ old('gratis_pengantaran', $gratisPengantaran->value) }}">
                            @error('gratis_pengantaran')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jarak_maksimum_pengantaran" class="form-label">Jarak Maksimum Pengantaran (Meter)</label>
                            <input type="number" name="jarak_maksimum_pengantaran" id="jarak_maksimum_pengantaran" class="form-control @error('jarak_maksimum_pengantaran') is-invalid @enderror" min="0" step="1" required autocomplete="off" placeholder="Jarak Maksimum Pengantaran (Meter)" value="{{ old('jarak_maksimum_pengantaran', $jarakMaksimumPengantaran->value) }}">
                            @error('jarak_maksimum_pengantaran')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="selang_waktu_penjemputan" class="form-label">Selang Waktu Penjemputan (Jam)</label>
                            <input type="number" name="selang_waktu_penjemputan" id="selang_waktu_penjemputan" class="form-control @error('selang_waktu_penjemputan') is-invalid @enderror" min="0" step="1" required autocomplete="off" placeholder="Selang Waktu Penjemputan (Jam)" value="{{ old('selang_waktu_penjemputan', $selangWaktuPenjemputan->value) }}">
                            @error('selang_waktu_penjemputan')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email_utama" class="form-label">Email Utama</label>
                            <input type="email" name="email_utama" id="email_utama" class="form-control @error('email_utama') is-invalid @enderror" required autocomplete="off" placeholder="Email Utama" value="{{ old('email_utama', $emailUtama->value) }}">
                            @error('email_utama')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-control @error('nomor_whatsapp') is-invalid @enderror" required autocomplete="off" placeholder="Nomor WhatsApp" value="{{ old('nomor_whatsapp', $nomorWhatsapp->value) }}">
                            @error('nomor_whatsapp')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="akun_instagram" class="form-label">Akun Instagram</label>
                            <input type="text" name="akun_instagram" id="akun_instagram" class="form-control @error('akun_instagram') is-invalid @enderror" required autocomplete="off" placeholder="Akun Instagram" value="{{ old('akun_instagram', $akunInstagram->value) }}">
                            @error('akun_instagram')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection