<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/customer/{whatsapp_number}', function(string $whatsappNumber) {
    $customer = Customer::where('whatsapp_number', $whatsappNumber)->first();
    if ($customer) {
        return response()->json([
            'status' => 'OK',
            'message' => 'Data ditemukan',
            'data' => $customer
        ], 200);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
});
