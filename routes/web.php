<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});


// == User ==
// LANDING PAGE

// ORDER PAGE
// -> Ada button untuk cek nomor terdaftar jika customer pernah bertransaksi sebelumnya sehingga tidak perlu lagi mengisi nama, alamat, dll pada form (Hit API, cek ke route api.php)

// TRACKING PAGE
// -> Cek transaksi berdasarkan nomor invoice, menampilkan transaksi dan detail transaksi. (Kemungkinan hit API juga, data tampil di halaman yang sama, atau dapat berupa sehingga user tidak ter-redirect ke halaman lain)

// INVOICE PAGE



// == Dashboard Admin ==

// *Note:
// Untuk role admin, hanya bisa mengakses menu transaction pending
// Untuk role kurir, hanya bisa mengakses menu transaction pending yang tipe transaksinya pickup & delivery

Route::get('/login', function() {
    return view('login.index');
});

// Main Dashborad

// CMS CAMPAIGN (CRUD)

// CMS GALLERY (CRUD)

// TRANSACTION PENDING
// -> edit status pembayaran (paid:unpaid)
// -> edit status transaksi (pending -> on_progress -> done)
// -> tombol cetak label (no invoice)

// TRANSACTION PENDING -> type: Pickup & Delivery
// -> redirect link ke google maps berdasarkan koordinat customer
// -> redirect link ke whatsapp dengan kalimat template (pemberitahuan keberangkatan)
// -> redirect link ke whatsapp dengan kalimat template (pemberitahuan sampai di lokasi)

// TRANSACTION REPORT (Export Excel)
// -> per hari
// -> per minggu
// -> per bulan
// -> per tahun
// -> custom date

