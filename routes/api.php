<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route API ada di sini. Secara default akan memiliki prefix "/api"
| dan middleware "api". Untuk autentikasi kita pakai JWT (auth:api).
|
*/

// Auth
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (hanya bisa diakses jika sudah login dengan JWT token)
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Produk CRUD
    Route::apiResource('produk', ProdukController::class);

    // Report
    Route::get('/report/summary', [ReportController::class, 'summary']);
});
