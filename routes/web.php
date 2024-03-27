<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\TransactionDropzoneController;
use App\Http\Controllers\TransactionPDController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\InvoiceController;

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
Route::get('/order', [TransactionController::class, 'createStepOne']);
Route::post('/order', [TransactionController::class, 'storeStepOne']);

Route::get('/order/details', [TransactionController::class, 'createStepTwo']);
Route::post('/order/details', [TransactionController::class, 'storeStepTwo']);

Route::get('/order/summary', [TransactionController::class, 'createStepThree']);
Route::post('/order/summary', [TransactionController::class, 'storeStepThree']);

Route::get('/pickup-delivery', [TransactionController::class, 'createPDStepOne']);

// TRACKING PAGE
// -> Cek transaksi berdasarkan nomor invoice, menampilkan transaksi dan detail transaksi. (Kemungkinan hit API juga, data tampil di halaman yang sama, atau dapat berupa sehingga user tidak ter-redirect ke halaman lain)

// INVOICE PAGE
Route::get('/invoice/{invoice}', [InvoiceController::class, 'index']);


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

// Customer
Route::get('/dashboard/master-data/customer', [CustomerController::class, 'index']);

// Treatment
Route::resource('/dashboard/master-data/treatment', TreatmentController::class)->except(['edit', 'update']);
Route::post('/dashboard/master-data/treatment/{treatment}', [TreatmentController::class, 'storeDetail']);
Route::put('/dashboard/master-data/treatment/{treatment}/{detailTreatment}', [TreatmentController::class, 'updateDetail']);
Route::delete('/dashboard/master-data/treatment/{treatment}/{detailTreatment}', [TreatmentController::class, 'destroyDetail']);

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
Route::get('/dashboard/transaction/pickup-delivery', [TransactionPDController::class, 'index']);
Route::put('/dashboard/transaction/pickup-delivery/{transaction}/payment-update', [TransactionPDController::class, 'paymentUpdate']);
Route::put('/dashboard/transaction/pickup-delivery/{transaction}/status-update', [TransactionPDController::class, 'statusUpdate']);

// TRANSACTION REPORT (Export Excel)
// -> per hari
// -> per minggu
// -> per bulan
// -> per tahun
// -> custom date

