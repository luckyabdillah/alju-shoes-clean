<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\TransactionDropzoneController;
use App\Http\Controllers\TransactionPDController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index']);
Route::get('/treatment/{treatment}', [HomeController::class, 'getTreatment']);
Route::get('/treatment/{treatment}/get-details', [HomeController::class, 'getTreatmentDetails']);
Route::get('/check-promo-code/{code}', [HomeController::class, 'checkPromoCode']);

Route::get('/order', [TransactionDropzoneController::class, 'createStepOne']);
Route::post('/order', [TransactionDropzoneController::class, 'storeStepOne']);

Route::get('/order/details', [TransactionDropzoneController::class, 'createStepTwo']);
Route::post('/order/details', [TransactionDropzoneController::class, 'storeStepTwo']);

Route::get('/order/summary', [TransactionDropzoneController::class, 'createStepThree']);
Route::post('/order/summary', [TransactionDropzoneController::class, 'storeStepThree']);

Route::get('/pickup-delivery', [TransactionPDController::class, 'createStepOne']);
Route::post('/pickup-delivery', [TransactionPDController::class, 'storeStepOne']);

Route::get('/pickup-delivery/details', [TransactionPDController::class, 'createStepTwo']);
Route::post('/pickup-delivery/details', [TransactionPDController::class, 'storeStepTwo']);

Route::get('/pickup-delivery/summary', [TransactionPDController::class, 'createStepThree']);
Route::post('/pickup-delivery/summary', [TransactionPDController::class, 'storeStepThree']);

Route::get('/transaction/{transaction}', [TransactionDropzoneController::class, 'show']);

Route::get('/invoice/{transaction}', [InvoiceController::class, 'index']);
Route::get('/invoice/{transaction}/export', [InvoiceController::class, 'export']);
Route::put('/invoice/{transaction}', [InvoiceController::class, 'update']);

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);

        Route::middleware(['role:operation|administrator'])->group(function () {
            Route::get('/transaction/dropzone', [TransactionController::class, 'indexDropzone']);
            Route::put('/transaction/dropzone/{transaction}/payment-update', [TransactionController::class, 'paymentUpdate']);
            Route::put('/transaction/dropzone/{transaction}/status-update', [TransactionController::class, 'statusUpdate']);
            Route::delete('/transaction/dropzone/{transaction}', [TransactionController::class, 'destroy']);
        });

        Route::middleware(['role:driver|administrator'])->group(function () {
            Route::get('/transaction/pickup-delivery', [TransactionController::class, 'indexPickupDelivery']);
            Route::put('/transaction/pickup-delivery/{transaction}/payment-update', [TransactionController::class, 'paymentUpdate']);
            Route::put('/transaction/pickup-delivery/{transaction}/status-update', [TransactionController::class, 'statusUpdate']);
            Route::delete('/transaction/pickup-delivery/{transaction}', [TransactionController::class, 'destroy']);
        });
    
        Route::middleware(['role:administrator'])->group(function () {
            Route::prefix('master-data')->group(function () {
                Route::resource('/campaign', CampaignController::class);
                Route::put('/campaign/{campaign}/status-update', [CampaignController::class, 'statusUpdate']);
                
                Route::resource('/gallery', GalleryController::class);
                Route::put('/gallery/{gallery}/status-update', [GalleryController::class, 'statusUpdate']);
                
                Route::resource('/outlet', OutletController::class);
                Route::put('/outlet/{outlet}/status-update', [OutletController::class, 'statusUpdate']);
                
                Route::get('/customer', [CustomerController::class, 'index']);
                
                Route::resource('/treatment', TreatmentController::class);
                Route::post('/treatment/{treatment}', [TreatmentController::class, 'storeDetail']);
                Route::put('/treatment/{treatment}/{detailTreatment}', [TreatmentController::class, 'updateDetail']);
                Route::delete('/treatment/{treatment}/{detailTreatment}', [TreatmentController::class, 'destroyDetail']);
                
                Route::resource('/promo-code', PromoCodeController::class);
            });
            
            Route::resource('/user', UserController::class);
            Route::put('/user/{user}/reset-password', [UserController::class, 'resetPassword']);
            
            Route::get('/report', [ReportController::class, 'index']);
            Route::get('/report/export-excel', [ReportController::class, 'exportExcel']);
        
            Route::get('/config', [ConfigController::class, 'index']);
            Route::put('/config', [ConfigController::class, 'update']);
        
            Route::get('/transaction', [TransactionController::class, 'index']);
            Route::get('/transaction/{transaction}/edit', [TransactionController::class, 'edit']);
            Route::put('/transaction/{transaction}', [TransactionController::class, 'update']);
        });

        Route::get('/settings', [SettingsController::class, 'edit']);
        Route::put('/settings', [SettingsController::class, 'update']);
        Route::put('/change-password', [SettingsController::class, 'updatePassword']);
    });
    
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');
