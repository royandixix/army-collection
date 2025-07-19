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

// USER
use App\Http\Controllers\User\ProdukUserController;
use App\Http\Controllers\User\KeranjangController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\RiwayatController;

//
// ==============================
// 🔐 AUTHENTIKASI (LOGIN / REGISTER)
// ==============================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

//
// ==============================
// 🏠 DASHBOARD (ADMIN/UMUM)
// ==============================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

//
// ==============================
// 🛠️ ADMIN ROUTES
// ==============================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // 👥 Manajemen Pengguna
    Route::get('/manajemen/pengguna', [UserController::class, 'index'])->name('manajemen.manajemen_pengguna');
    Route::get('/manajemen/pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen/pengguna/{id}', [UserController::class, 'update'])->name('users.update');

    // 📦 Manajemen Produk
    Route::get('/manajemen/produk', [AdminProdukController::class, 'index'])->name('manajemen.manajemen_produk');
    Route::get('/manajemen/produk/create', [AdminProdukController::class, 'create'])->name('manajemen.manajemen_produk_create');
    Route::post('/manajemen/produk', [AdminProdukController::class, 'store'])->name('manajemen.manajemen_produk_store');
    Route::get('/manajemen/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('manajemen.manajemen_produk_edit');
    Route::put('/manajemen/produk/{id}', [AdminProdukController::class, 'update'])->name('manajemen.manajemen_produk_update');
    Route::get('/manajemen/produk/{id}/delete', [AdminProdukController::class, 'destroy'])->name('manajemen.manajemen_produk_destroy');
    

    // 🧾 Manajemen Penjualan
    Route::get('/manajemen/penjualan', [PenjualanController::class, 'index'])->name('manajemen.manajemen_penjualan');
    Route::get('/manajemen/penjualan/create', [PenjualanController::class, 'create'])->name('manajemen.manajemen_penjualan_create');
    Route::post('/manajemen/penjualan', [PenjualanController::class, 'store'])->name('manajemen.manajemen_penjualan_store');
    Route::get('/manajemen/penjualan/{id}', [PenjualanController::class, 'show'])->name('manajemen.manajemen_penjualan_show');
    Route::get('/manajemen/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('manajemen.manajemen_penjualan_edit');
    Route::put('/manajemen/penjualan/{id}', [PenjualanController::class, 'update'])->name('manajemen.manajemen_penjualan_update');
    Route::delete('/manajemen/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('manajemen.manajemen_penjualan_destroy');

    // 👤 Manajemen Pelanggan
    Route::get('/manajemen/pelanggan', [PelangganController::class, 'index'])->name('manajemen.manajemen_pelanggan');
    Route::get('/manajemen/pelanggan/create', [PelangganController::class, 'create'])->name('manajemen.manajemen_pelanggan_create');
    Route::post('/manajemen/pelanggan', [PelangganController::class, 'store'])->name('manajemen.manajemen_pelanggan_store');
    Route::get('/manajemen/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('manajemen.manajemen_pelanggan_edit');
    Route::put('/manajemen/pelanggan/{id}', [PelangganController::class, 'update'])->name('manajemen.manajemen_pelanggan_update');
    Route::delete('/manajemen/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('manajemen.manajemen_pelanggan_destroy');

    // 📄 Laporan
    Route::get('/laporan/faktur', [LaporanController::class, 'index'])->name('laporan.faktur_laporan');
    Route::get('/laporan/faktur/{id}', [LaporanController::class, 'show'])->name('laporan.faktur_laporan_show');
});

//
// ==============================
// 👤 USER ROUTES
// ==============================
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {

    // 🛍️ Produk
    Route::get('/produk', [ProdukUserController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukUserController::class, 'show'])->name('produk.show');

    // 🛒 Tambah ke Keranjang via ProdukController
    Route::post('/keranjang', [ProdukUserController::class, 'tambahKeKeranjang'])->name('keranjang.store');

    // 🛒 Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.hapus');
    Route::post('/keranjang/update', [KeranjangController::class, 'updateJumlah'])->name('keranjang.update');


    // 💳 Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');

    // 📄 Riwayat Transaksi
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});
