<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{slug}', [ProductController::class, 'details']);

Route::post('/addProduk', [ProductController::class, 'addProduk']);
Route::post('/updateProduk/{id_produk}', [ProductController::class, 'updateProduk']);

Route::post('/addKategori', [ProductController::class, 'addKategori']);
Route::post('/updateKategori/{id_kategori_produk}', [ProductController::class, 'updateKategori']);
Route::post('/deleteKategori/{id_kategori_produk}', [ProductController::class, 'deleteKategori']);

Route::post('/addSatuan', [ProductController::class, 'addSatuan']);
Route::post('/updateSatuan/{id_satuan}', [ProductController::class, 'updateSatuan']);
Route::post('/deleteSatuan/{id_satuan}', [ProductController::class, 'deleteSatuan']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
