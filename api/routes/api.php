<?php

use App\Http\Controllers\AnalisisHPPController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Models\PengadaanBarang;
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

// Product
Route::get('/product', [ProductController::class, 'index']);
Route::get('/category', [ProductController::class, 'kategori']);
Route::get('/satuan', [ProductController::class, 'satuan']);
Route::get('/product/{slug}', [ProductController::class, 'details']);

Route::post('/addProduk', [ProductController::class, 'addProduk']);
Route::post('/updateProduk/{id_produk}', [ProductController::class, 'updateProduk']);
Route::post('/updateImage/{id_produk}', [ProductController::class, 'updateImage']);
Route::post('/deleteProduk/{id_produk}', [ProductController::class, 'deleteProduk']);

Route::post('/addKategori', [ProductController::class, 'addKategori']);
Route::post('/updateKategori/{id_kategori_produk}', [ProductController::class, 'updateKategori']);
Route::post('/deleteKategori/{id_kategori_produk}', [ProductController::class, 'deleteKategori']);

Route::post('/addSatuan', [ProductController::class, 'addSatuan']);
Route::post('/updateSatuan/{id_satuan}', [ProductController::class, 'updateSatuan']);
Route::post('/deleteSatuan/{id_satuan}', [ProductController::class, 'deleteSatuan']);

Route::post('/updateHarga', [ProductController::class, 'updateHarga']);

Route::get('/bahanBaku', [MaterialController::class, 'index']);
Route::post('/addBahanBaku', [MaterialController::class, 'addDataMaterial']);

Route::get('/barangMasuk', [BarangMasukController::class, 'index']);
Route::get('/laporanBarangMasuk', [BarangMasukController::class, 'laporanBarangMasuk']);
Route::post('/addStok', [BarangMasukController::class, 'addStok']);

Route::get('/pengadaan', [PengadaanController::class, 'index']);
Route::get('/pengadaan/{id_pengadaan}', [PengadaanController::class, 'details']);

Route::post('/addPengadaan', [PengadaanController::class, 'addPengadaan']);
Route::post('/updatePengadaan/{id_pengadaan}', [PengadaanController::class, 'updatePengadaan']);
Route::post('/validasiPengadaan/{id_pengadaan}', [PengadaanController::class, 'validasiPengadaan']);
Route::post('/deletePengadaan/{id_pengadaan}', [PengadaanController::class, 'deletePengadaan']);

Route::get('/transaksi', [TransaksiPenjualanController::class, 'index']);
Route::get('/transaksi/{id_transaksi}', [TransaksiPenjualanController::class, 'detailTransaksi']);
Route::get('/dataTransaksi', [TransaksiPenjualanController::class, 'dataTransaksi']);
Route::get('/laporanPenjualan', [TransaksiPenjualanController::class, 'laporanPenjualan']);

Route::post('/submitTransaksi', [TransaksiPenjualanController::class, 'submitTransaksi']);
Route::post('/changeStatus/{id_transaksi}', [TransaksiPenjualanController::class, 'changeStatus']);

Route::get('/analisis-hpp', [AnalisisHPPController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
