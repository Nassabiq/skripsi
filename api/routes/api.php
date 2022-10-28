<?php

use App\Http\Controllers\AnalisisHPPController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\PengadaanPersediaanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\TransaksiPenjualanController;
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

// Produk
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/kategori', [ProdukController::class, 'kategori']);
Route::get('/produk/{id_produk}', [ProdukController::class, 'detail']);

Route::post('/produk', [ProdukController::class, 'addProduk']);
Route::patch('/produk/{id_produk}', [ProdukController::class, 'updateProduk']);
Route::patch('/produk/image/{id_produk}', [ProdukController::class, 'updateImage']);
Route::delete('/produk/{id_produk}', [ProdukController::class, 'deleteProduk']);

// Route::post('/addKategori', [ProductController::class, 'addKategori']);
// Route::post('/updateKategori/{id_kategori_produk}', [ProductController::class, 'updateKategori']);
// Route::post('/deleteKategori/{id_kategori_produk}', [ProductController::class, 'deleteKategori']);

// Route::post('/updateHarga', [ProductController::class, 'updateHarga']);

Route::get('/bahan-baku', [BahanBakuController::class, 'index']);
Route::post('/bahan-baku', [BahanBakuController::class, 'addBahanBaku']);
Route::put('/bahan-baku/{id_bahan_baku}', [BahanBakuController::class, 'editBahanBaku']);
Route::delete('/bahan-baku/{id_bahan_baku}', [BahanBakuController::class, 'deleteBahanBaku']);

Route::get('/stok-masuk', [StokMasukController::class, 'index']);
Route::post('/stok-masuk', [StokMasukController::class, 'addStok']);

Route::get('/laporan-stok-masuk', [StokMasukController::class, 'laporanBarangMasuk']);

Route::get('/pengadaan', [PengadaanPersediaanController::class, 'index']);
Route::get('/pengadaan/{id_pengadaan}', [PengadaanPersediaanController::class, 'details']);

Route::post('/pengadaan', [PengadaanPersediaanController::class, 'addPengadaan']);
Route::patch('/pengadaan/{id_pengadaan}', [PengadaanPersediaanController::class, 'updatePengadaan']);
Route::patch('/pengadaan/validasi/{id_pengadaan}', [PengadaanPersediaanController::class, 'validasiPengadaan']);
Route::delete('/pengadaan/{id_pengadaan}', [PengadaanPersediaanController::class, 'deletePengadaan']);

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
