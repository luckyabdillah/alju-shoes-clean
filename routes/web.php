<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\TransactionDropzoneController;
use App\Http\Controllers\TransactionController;

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
    return view('welcome');
});


// == User ==
// LANDING PAGE

// ORDER PAGE
// -> Ada button untuk cek nomor terdaftar jika customer pernah bertransaksi sebelumnya sehingga tidak perlu lagi mengisi nama, alamat, dll pada form (Hit API, cek ke route api.php)
Route::get('/order', [TransactionController::class, 'index']);
Route::post('/order', [TransactionController::class, 'store']);

// TRACKING PAGE
// -> Cek transaksi berdasarkan nomor invoice, menampilkan transaksi dan detail transaksi. (Kemungkinan hit API juga, data tampil di halaman yang sama, atau dapat berupa sehingga user tidak ter-redirect ke halaman lain)

// INVOICE PAGE



// == Dashboard Admin ==

// *Note:
// Untuk role admin, hanya bisa mengakses menu transaction pending
// Untuk role kurir, hanya bisa mengakses menu transaction pending yang tipe transaksinya pickup & delivery

Route::get('/login', function() {
    return view('dashboard.login.index');
});

// Main Dashborad
Route::get('/dashboard', [DashboardController::class, 'index']);

// CMS CAMPAIGN (CRUD)
Route::resource('/dashboard/master-data/campaign', CampaignController::class);
Route::put('/dashboard/master-data/campaign/{campaign}/status-update', [CampaignController::class, 'statusUpdate']);

// CMS GALLERY (CRUD)
Route::resource('/dashboard/master-data/gallery', GalleryController::class);
Route::put('/dashboard/master-data/gallery/{gallery}/status-update', [GalleryController::class, 'statusUpdate']);

// CMS OUTLET (CRUD)
Route::resource('/dashboard/master-data/outlet', OutletController::class);
Route::put('/dashboard/master-data/outlet/{outlet}/status-update', [OutletController::class, 'statusUpdate']);

// TRANSACTION PENDING
// -> edit status pembayaran (paid:unpaid)
// -> edit status transaksi (pending -> on_progress -> done)
// -> tombol cetak label (no invoice)
Route::get('/dashboard/transaction/dropzone', [TransactionDropzoneController::class, 'index']);
Route::put('/dashboard/transaction/dropzone/{transaction}/payment-update', [TransactionDropzoneController::class, 'paymentUpdate']);
Route::put('/dashboard/transaction/dropzone/{transaction}/status-update', [TransactionDropzoneController::class, 'statusUpdate']);

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

