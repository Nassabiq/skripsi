<?php

use App\Http\Controllers\AnalisisHPPController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Produk                  
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/kategori', [ProdukController::class, 'kategori']);
Route::get('/katalog', [ProdukController::class, 'katalog']);

Route::post('/transaksi/notification', [TransaksiController::class, 'callback']);

Route::post('/refresh', fn () =>  auth()->refresh());
Route::group(['middleware' => ['auth:api']], function () {
    // Produk
    Route::get('/produk/{id_produk}', [ProdukController::class, 'detail']);

    Route::get('/harga', [ProdukController::class, 'harga']);
    Route::post('/harga', [ProdukController::class, 'updateHarga']);

    Route::post('/produk', [ProdukController::class, 'addProduk']);
    Route::patch('/produk/{id_produk}', [ProdukController::class, 'updateProduk']);
    Route::patch('/produk/image/{id_produk}', [ProdukController::class, 'updateImage']);
    Route::delete('/produk/{id_produk}', [ProdukController::class, 'deleteProduk']);

    // BAHAN BAKU
    Route::get('/bahan-baku', [BahanBakuController::class, 'index']);
    Route::post('/bahan-baku', [BahanBakuController::class, 'addBahanBaku']);
    Route::put('/bahan-baku/{id_bahan_baku}', [BahanBakuController::class, 'editBahanBaku']);
    Route::delete('/bahan-baku/{id_bahan_baku}', [BahanBakuController::class, 'deleteBahanBaku']);

    // CART
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::delete('/cart/{id_cart}', [CartController::class, 'removeCart']);

    // TRANSAKSI
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksi/all', [TransaksiController::class, 'dataTransaksi']);
    Route::get('/transaksi/{id_transaksi}', [TransaksiController::class, 'detailTransaksi']);
    Route::get('/transaksi/invoice/{invoice_id}', [TransaksiController::class, 'getInvoice']);

    Route::post('/transaksi', [TransaksiController::class, 'submitTransaksi']);
    Route::post('/transaksi/status/{id_transaksi}', [TransaksiController::class, 'changeStatus']);
    Route::post('/transaksi/resi/{id_transaksi}', [TransaksiController::class, 'inputResi']);

    // STOK MASUK
    Route::get('/stok-masuk', [StokMasukController::class, 'index']);
    Route::post('/stok-masuk', [StokMasukController::class, 'addStok']);

    // PENGADAAN PERSEDIAAN
    Route::get('/pengadaan', [PengadaanController::class, 'index']);
    Route::get('/pengadaan/{id_pengadaan}', [PengadaanController::class, 'details']);

    Route::post('/pengadaan', [PengadaanController::class, 'addPengadaan']);
    Route::patch('/pengadaan/{id_pengadaan}', [PengadaanController::class, 'updatePengadaan']);
    Route::patch('/pengadaan/validasi/{id_pengadaan}', [PengadaanController::class, 'validasiPengadaan']);
    Route::delete('/pengadaan/{id_pengadaan}', [PengadaanController::class, 'deletePengadaan']);

    // LAPORAN
    Route::get('/laporan/penjualan', [LaporanController::class, 'laporanPenjualan']);
    Route::get('/laporan/stok-masuk', [LaporanController::class, 'laporanStokMasuk']);
    Route::get('/laporan/omset', [LaporanController::class, 'laporanOmset']);

    // USER
    Route::get('/user', fn () => auth()->user()->load('role', 'pelanggan'));
    Route::get('/all-user', [UserController::class, 'index']);
    Route::get('/role', [UserController::class, 'role']);

    Route::post('/user', [UserController::class, 'addUser']);
    Route::patch('/user/{id_user}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id_user}  ', [UserController::class, 'deleteUser']);

    // ANALISIS
    Route::get('/sku/bahan-baku', [AnalisisHPPController::class, 'getBahanBaku']);
    Route::get('/analisis-hpp', [AnalisisHPPController::class, 'analisis']);

    Route::post('/logout', [AuthController::class, 'logout']);
});


// Route::post('/addKategori', [ProductController::class, 'addKategori']);
// Route::post('/updateKategori/{id_kategori_produk}', [ProductController::class, 'updateKategori']);
// Route::post('/deleteKategori/{id_kategori_produk}', [ProductController::class, 'deleteKategori']);
