<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\RekapController;

// USER
use App\Http\Controllers\User\ProdukUserController;
use App\Http\Controllers\User\KeranjangController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\ProfilController;



// ==============================
// 🔐 AUTH (LOGIN / REGISTER)
// ==============================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ==============================
// 🛠️ ADMIN ROUTES
// ==============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // 📊 Dashboard & Search
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/live', [SearchController::class, 'liveSearch'])->name('search.live');

    // 📄 Faktur
    Route::get('/laporan/cetak-semua', [LaporanController::class, 'cetakSemua'])->name('faktur_laporan.semua_pdf');
    Route::get('/laporan/faktur', [LaporanController::class, 'index'])->name('laporan.faktur_laporan');
    Route::get('/laporan/faktur/{id}', [LaporanController::class, 'show'])->name('laporan.faktur_laporan_show');

    // 👥 Manajemen Pengguna
    Route::get('/manajemen/pengguna', [UserController::class, 'index'])->name('manajemen.manajemen_pengguna');
    Route::get('/manajemen/pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen/pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manajemen/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // 📦 Manajemen Produk
    Route::get('/manajemen/produk', [AdminProdukController::class, 'index'])->name('manajemen.manajemen_produk');
    Route::get('/manajemen/produk/create', [AdminProdukController::class, 'create'])->name('manajemen.manajemen_produk_create');
    Route::post('/manajemen/produk', [AdminProdukController::class, 'store'])->name('manajemen.manajemen_produk_store');
    Route::get('/manajemen/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('manajemen.manajemen_produk_edit');
    Route::put('/manajemen/produk/{id}', [AdminProdukController::class, 'update'])->name('manajemen.manajemen_produk_update');
    Route::get('/manajemen/produk/{id}/delete', [AdminProdukController::class, 'destroy'])->name('manajemen.manajemen_produk_destroy');

    // 📈 Penjualan
    Route::get('/manajemen/penjualan', [PenjualanController::class, 'index'])->name('manajemen.manajemen_penjualan');
    Route::get('/manajemen/penjualan/create', [PenjualanController::class, 'create'])->name('manajemen.manajemen_penjualan_create');
    Route::post('/manajemen/penjualan', [PenjualanController::class, 'store'])->name('manajemen.manajemen_penjualan_store');
    Route::get('/manajemen/penjualan/{id}', [PenjualanController::class, 'show'])->name('manajemen.manajemen_penjualan_show');
    Route::get('/manajemen/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('manajemen.manajemen_penjualan_edit');
    Route::put('/manajemen/penjualan/{id}', [PenjualanController::class, 'update'])->name('manajemen.manajemen_penjualan_update');
    Route::delete('/manajemen/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('manajemen.manajemen_penjualan_destroy');
    Route::patch('/manajemen/penjualan/{id}/status', [PenjualanController::class, 'ubahStatus'])->name('manajemen.penjualan_ubah_status');
    Route::post('/manajemen/penjualan/store-manual', [PenjualanController::class, 'storeManual'])->name('manajemen.manajemen_penjualan_store_manual');


    // 👤 Pelanggan
    Route::get('/manajemen/pelanggan', [PelangganController::class, 'index'])->name('manajemen.manajemen_pelanggan');
    Route::get('/manajemen/pelanggan/create', [PelangganController::class, 'create'])->name('manajemen.manajemen_pelanggan_create');
    Route::post('/manajemen/pelanggan', [PelangganController::class, 'store'])->name('manajemen.manajemen_pelanggan_store');
    Route::get('/manajemen/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('manajemen.manajemen_pelanggan_edit');
    Route::put('/manajemen/pelanggan/{id}', [PelangganController::class, 'update'])->name('manajemen.manajemen_pelanggan_update');
    Route::delete('/manajemen/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('manajemen.manajemen_pelanggan_destroy');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Laporan Produk

        Route::get('/produk', [\App\Http\Controllers\Admin\LaporanDataController::class, 'produk'])->name('produk');
        Route::get('/produk/cetak', [\App\Http\Controllers\Admin\LaporanDataController::class, 'cetakProduk'])->name('produk.cetak');

        // Laporan Pelanggan
        Route::get('/pelanggan', [\App\Http\Controllers\Admin\LaporanDataController::class, 'pelanggan'])->name('pelanggan');

        // Pembelian
        Route::get('/pembelian', [\App\Http\Controllers\Admin\LaporanDataController::class, 'pembelian'])->name('pembelian');
        Route::get('/pembelian/cetak', [\App\Http\Controllers\Admin\LaporanDataController::class, 'cetakPembelian'])->name('pembelian.cetak');

        // Penjualan
        Route::get('/penjualan', [\App\Http\Controllers\Admin\LaporanDataController::class, 'penjualan'])->name('penjualan');
        Route::get('/penjualan/cetak', [\App\Http\Controllers\Admin\LaporanDataController::class, 'cetakPenjualan'])->name('penjualan.cetak');
    });


    // 📊 Rekap
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
});



// 👤 USER ROUTES

Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {

    /// 🛍️ Produk
    Route::get('/produk', [ProdukUserController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukUserController::class, 'show'])->name('produk.show');

    // 🛒 Keranjang & Checkout
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [ProdukUserController::class, 'tambahKeKeranjang'])->name('keranjang.tambah');
    Route::post('/keranjang/update', [KeranjangController::class, 'updateJumlah'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy'); // rename agar konsisten
    Route::post('/keranjang/checkout', [KeranjangController::class, 'prosesCheckout'])->name('keranjang.checkout');

    // 📄 Riwayat Transaksi
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.hapus');

    // 👤 Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
});
