<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', function () {
    return redirect('/saleorder');
});

Route::get('/saleorder',[UserController::class, 'saleorder']);
Route::get('/pilihmeja',[UserController::class,'pilihmeja']);
Route::post('/savesaleorder',[UserController::class, 'savesaleorder']);
Route::get('/listsaleorder/{id}',[UserController::class,'listsaleorder']);

Route::get('/reservasi',[UserController::class,'reservasi']);
Route::post('/savereservasi',[UserController::class,'savereservasi']);

route::get('/cekpelanggan',[UserController::class,'cekpelanggan']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['isLogin'])->controller(AdminController::class)->group(function () {
    // Profile
    Route::get('/profile', 'profile');
    Route::put('/profileupdate', 'profileupdate');

    Route::get('/dashboard', 'dashboard');

    //Stok 
    Route::get('/stok', 'stok');
    Route::get('/tambahstok', 'tambahstok');
    Route::post('/tambahstoksimpan', 'tambahstoksimpan');
    Route::get('/tambahstokedit/{id}', 'tambahstokedit');
    Route::put('/tambahstokeditsimpan/{id}', 'tambahstokeditsimpan');
    Route::delete('/stokhapus/{id}', 'stokhapus');

    //Kategori
    Route::get('/kategori','kategori');
    Route::get('/tambahkategori','tambahkategori');
    Route::post('/tambahkategorisimpan','tambahkategorisimpan');
    Route::get('/kategoriedit/{id}','kategoriedit');
    Route::put('/kategorieditsimpan/{id}','kategorieditsimpan');
    Route::delete('/kategorihapus/{id}','kategorihapus');

    //Pelanggan
    Route::get('/pelanggan', 'pelanggan');
    Route::get('/tambahpelanggan', 'tambahpelanggan');
    Route::post('/tambahpelanggansimpan', 'tambahpelanggansimpan');
    Route::get('/pelangganedit/{id}', 'pelangganedit');
    Route::put('/pelangganeditsimpan/{id}', 'pelangganeditsimpan');
    Route::delete('/pelangganhapus/{id}', 'pelangganhapus');


    //Pembelian
    Route::get('/pembelian', 'pembelian');
    Route::get('/pembeliantambah', 'pembeliantambah');
    Route::post('/pembeliantambahsimpan', 'pembeliantambahsimpan');
    Route::get('/pembelianedit/{id}', 'pembelianedit');
    Route::put('/pembelianeditsimpan/{id}', 'pembelianeditsimpan');
    Route::delete('/pembelianhapus/{id}', 'pembelianhapus');
    Route::get('/pembeliandetail/', 'pembeliandetail');


    //Penjualan
    Route::get('/penjualan','penjualan');
    Route::get('/penjualantambah','penjualantambah');
    Route::post ('penjualantambahsimpan','penjualantambahsimpan');
    Route::get('/penjualanedit/{id}','penjualanedit');
    Route::get('/penjualaneditsimpan/{id}','penjualaneditsimpan');
    Route::get('/penjualanhapus','penjualanhapus');
    Route::get('/pembeliandetail','pembeliandetail');
    Route::get('/penjualandetail/{id}', 'penjualandetail');


    //Sales Order
    // Route::get('/saleorder','saleorder');
    // Route::get('/saleordertambah','saleordertambah');
    // Route::post('saleordertambahsimpan','saleordertambahsimpan');
    // Route::get('/saleorderedit/{id}','saleorderedit');
    // Route::put('/saleordereditsimpan/{id}','saleordereditsimpan');
    // Route::delete('/saleorderhapus/{id}','saleorderhapus');
    // Route::get('/saleorderdetail/{id}','saleorderdetail');


});