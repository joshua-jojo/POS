<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\laporan\LabaRugiController;
use App\Http\Controllers\master\JenisPembayaranController;
use App\Http\Controllers\master\KategoriController;
use App\Http\Controllers\master\MemberController;
use App\Http\Controllers\master\ProdukController;
use App\Http\Controllers\master\SatuanController;
use App\Http\Controllers\master\SupplierController;
use App\Http\Controllers\pengaturan\PerusahaanController;
use App\Http\Controllers\pengaturan\ProfilController;
use App\Http\Controllers\pengaturan\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\transaksi\ClosingController;
use App\Http\Controllers\transaksi\DataPembelian;
use App\Http\Controllers\transaksi\DataPenjualanController;
use App\Http\Controllers\transaksi\HutangController;
use App\Http\Controllers\transaksi\PembelianController;
use App\Http\Controllers\transaksi\PengeluaranController;
use App\Http\Controllers\transaksi\PenjualanController;
use App\Http\Controllers\transaksi\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['prefix' => 'pengaturan', 'as' => 'pengaturan.', 'middleware' => ['auth', 'perusahaan']], function () {
    Route::resource('profil', ProfilController::class);
    Route::resource('users', UsersController::class)->middleware(['admin']);

    Route::resource('perusahaan', PerusahaanController::class)->middleware(['admin']);
    Route::post('perusahaan/logo', [PerusahaanController::class, 'logo_update'])->name('perusahaan.logo_update');
});


Route::middleware(['auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class);
    // master 
    Route::prefix('master')->group(function () {
        Route::resource('satuan', SatuanController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('member', MemberController::class);
        Route::get('member/print/all', [MemberController::class,'print_all'])->name('member.print_all');
        Route::resource('supplier', SupplierController::class);
        Route::get('supplier/print/all', [SupplierController::class,'print_all'])->name('supplier.print_all');
        Route::resource('jenis-pembayaran', JenisPembayaranController::class);
    });

    //transaksi
    Route::prefix('transaksi')->group(function () {
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::resource('penjualan', PenjualanController::class);
        Route::post('penjualan/cetak', [PenjualanController::class, 'penjualan_cetak'])->name('penjualan.cetak');
        Route::post('simpan', [TransaksiController::class, 'simpan_transaksi'])->name('transaksi.simpan');
        Route::post('simpan/hutang', [TransaksiController::class, 'hutang_transaksi'])->name('transaksi.hutang');
        Route::post('save', [TransaksiController::class, 'save_transaksi'])->name('transaksi.save');
        Route::delete('hapus/{transaksi}', [TransaksiController::class, 'hapus_transaksi'])->name('transaksi.hapus');
        Route::apiResource('hutang', HutangController::class);
        Route::apiResource('labarugi', LabaRugiController::class);
        Route::get('laba_rugi/print', [LabaRugiController::class,'print'])->name('labaRugi.print');
        Route::apiResource('pembelian', PembelianController::class);
        Route::post('pembelian/cetak', [PembelianController::class, 'pembelian_cetak'])->name('pembelian.cetak');
        Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
            Route::apiResource('penjualan', DataPenjualanController::class);
            Route::apiResource('pembelian', DataPembelian::class);
        });
        Route::apiResource('closing', ClosingController::class);
        Route::get('closing/print/{closing}', [ClosingController::class,'print'])->name('closing.print');
    });
    // pengaturan 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/foto', [ProfileController::class, 'foto'])->name('profile.foto');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/perusahaan', [PerusahaanController::class, 'create'])->name('perusahaan.create');
    Route::post('/perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
    Route::post('/theme', [ProfileController::class, 'theme'])->name('theme');

});

Route::get('print/lunas', [TransaksiController::class, 'print_lunas'])->name('print_lunas');
require __DIR__ . '/auth.php';
