<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\BuktiPembelianController;
use App\Http\Controllers\User\ProdukUserController;
use App\Http\Controllers\User\KeranjangController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\ProfilController;
use App\Http\Controllers\User\AlamatController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\LaporanDataController;

use App\Http\Controllers\User\NotifikasiController;

Route::get('/forgot-password/manual', [AuthController::class, 'showManualResetForm'])->name('password.manual');
Route::post('/forgot-password/manual', [AuthController::class, 'manualReset'])->name('password.manual.post');
Route::get('/forgot-password', [AuthController::class, 'showManualResetForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'manualReset'])->name('password.email');

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/live', [SearchController::class, 'liveSearch'])->name('search.live');

    Route::get('/laporan/cetak-semua', [LaporanController::class, 'cetakSemua'])->name('faktur_laporan.semua_pdf');
    Route::get('/laporan/faktur', [LaporanController::class, 'index'])->name('laporan.faktur_laporan');
    Route::get('/laporan/faktur/{id}', [LaporanController::class, 'show'])->name('laporan.faktur_laporan_show');

    Route::get('/manajemen/pengguna', [UserController::class, 'index'])->name('manajemen.manajemen_pengguna');
    Route::get('/manajemen/pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen/pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manajemen/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/manajemen/produk', [AdminProdukController::class, 'index'])->name('manajemen.manajemen_produk');
    Route::get('/manajemen/produk/create', [AdminProdukController::class, 'create'])->name('manajemen.manajemen_produk_create');
    Route::post('/manajemen/produk', [AdminProdukController::class, 'store'])->name('manajemen.manajemen_produk_store');
    Route::get('/manajemen/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('manajemen.manajemen_produk_edit');
    Route::put('/manajemen/produk/{id}', [AdminProdukController::class, 'update'])->name('manajemen.manajemen_produk_update');
    Route::get('/manajemen/produk/{id}/delete', [AdminProdukController::class, 'destroy'])->name('manajemen.manajemen_produk_destroy');

    Route::get('manajemen/penjualan/belum-bayar', [PenjualanController::class, 'belumBayar'])->name('manajemen.penjualan.belum_bayar');
    Route::get('/manajemen/penjualan', [PenjualanController::class, 'index'])->name('manajemen.manajemen_penjualan');
    Route::get('/manajemen/penjualan/create', [PenjualanController::class, 'create'])->name('manajemen.manajemen_penjualan_create');
    Route::post('/manajemen/penjualan', [PenjualanController::class, 'store'])->name('manajemen.manajemen_penjualan_store');
    Route::get('/manajemen/penjualan/{id}', [PenjualanController::class, 'show'])->name('manajemen.manajemen_penjualan_show');
    Route::get('/manajemen/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('manajemen.manajemen_penjualan_edit');
    Route::put('/manajemen/penjualan/{id}', [PenjualanController::class, 'update'])->name('manajemen.manajemen_penjualan_update');
    Route::delete('/manajemen/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('manajemen.manajemen_penjualan_destroy');

    // PATCH untuk ubah status penjualan
    Route::patch('/manajemen/penjualan/{id}/status', [PenjualanController::class, 'ubahStatus'])->name('manajemen.penjualan_ubah_status');

    // PATCH untuk update status pengiriman & bukti pengiriman
    Route::patch('/manajemen/penjualan/{id}/update-pengiriman', [PenjualanController::class, 'updatePengiriman'])
        ->name('penjualan.update_pengiriman');

    Route::post('/manajemen/penjualan/store-manual', [PenjualanController::class, 'storeManual'])->name('manajemen.manajemen_penjualan_store_manual');

    Route::get('/manajemen/pelanggan', [PelangganController::class, 'index'])->name('manajemen.manajemen_pelanggan');
    Route::get('/manajemen/pelanggan/create', [PelangganController::class, 'create'])->name('manajemen.manajemen_pelanggan_create');
    Route::post('/manajemen/pelanggan', [PelangganController::class, 'store'])->name('manajemen.manajemen_pelanggan_store');
    Route::get('/manajemen/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('manajemen.manajemen_pelanggan_edit');
    Route::put('/manajemen/pelanggan/{id}', [PelangganController::class, 'update'])->name('manajemen.manajemen_pelanggan_update');
    Route::delete('/manajemen/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('manajemen.manajemen_pelanggan_destroy');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/produk', [LaporanDataController::class, 'produk'])->name('produk');
        Route::get('/produk/cetak', [LaporanDataController::class, 'cetakProduk'])->name('produk.cetak');
        Route::get('/produk/terlaris', [LaporanDataController::class, 'produkTerlaris'])->name('produk.terlaris');
        Route::get('/laporan/produk-terlaris/cetak', [LaporanDataController::class, 'cetakProdukTerlaris'])->name('admin.laporan.cetak-produk-terlaris');
        Route::get('/produk/terlaris/cetak', [LaporanDataController::class, 'cetakProdukTerlaris'])->name('produk.terlaris.cetak');
        Route::get('/pelanggan', [LaporanDataController::class, 'pelanggan'])->name('pelanggan');
        Route::get('/pembelian', [LaporanDataController::class, 'pembelian'])->name('pembelian');
        Route::get('/pembelian/cetak', [LaporanDataController::class, 'cetakPembelian'])->name('pembelian.cetak');
        Route::get('/penjualan', [LaporanDataController::class, 'penjualan'])->name('penjualan');
        Route::get('/penjualan/cetak', [LaporanDataController::class, 'cetakPenjualan'])->name('penjualan.cetak');
        Route::get('/supplier', [LaporanDataController::class, 'supplier'])->name('supplier');
        Route::get('/supplier/cetak', [LaporanDataController::class, 'cetakSupplier'])->name('supplier.cetak');
    });

    Route::get('/bukti-pembelian', [BuktiPembelianController::class, 'index'])->name('bukti_pembelian.index');
    Route::post('/bukti-pembelian/{id}/upload', [BuktiPembelianController::class, 'upload'])->name('bukti_pembelian.upload');
    Route::post('/bukti-pembelian/{id}/update-status', [BuktiPembelianController::class, 'updateStatus'])->name('bukti_pembelian.update_status');
    Route::get('/bukti-pembelian/{id}/download', [BuktiPembelianController::class, 'download'])->name('bukti_pembelian.download');

    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
});

Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/produk', [ProdukUserController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukUserController::class, 'show'])->name('produk.show');

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [ProdukUserController::class, 'tambahKeKeranjang'])->name('keranjang.tambah');
    Route::post('/keranjang/update', [KeranjangController::class, 'updateJumlah'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::post('/keranjang/checkout', [KeranjangController::class, 'prosesCheckout'])->name('keranjang.checkout');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.hapus');
    Route::post('/riwayat/{id}/upload', [RiwayatController::class, 'uploadBuktiSubmit'])->name('riwayat.upload');
    // User upload bukti diterima
    Route::post('/riwayat/{id}/bukti-diterima', [\App\Http\Controllers\User\RiwayatController::class, 'uploadBuktiDiterima'])->name('riwayat.bukti_diterima');
    // Admin lihat bukti diterima user
    Route::get('/manajemen/penjualan/{id}/bukti-diterima', [\App\Http\Controllers\Admin\PenjualanController::class, 'lihatBuktiDiterima'])->name('manajemen.penjualan.bukti_diterima');



    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

    Route::get('/alamat/create', [AlamatController::class, 'create'])->name('alamat.create');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::patch('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.bacaSemua');
});

Route::prefix('admin/manajemen')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('produk', [ProdukController::class, 'index'])->name('admin.manajemen.manajemen_produk');
    Route::get('produk/kartu-stok/pdf', [ProdukController::class, 'kartuStokPdf'])->name('admin.manajemen.kartu_stok_pdf');
});
