<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;

// 🔐 Login & Logout
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 📝 Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 🏠 Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// 🛠️ Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // 👥 Manajemen Pengguna
    Route::get('/manajemen/pengguna', [UserController::class, 'index'])->name('manajemen.manajemen_pengguna');
    Route::get('/manajemen/pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen/pengguna/{id}', [UserController::class, 'update'])->name('users.update');

    // 📦 Manajemen Produk
    Route::get('/manajemen/produk', [ProdukController::class, 'index'])->name('manajemen.manajemen_produk');
    Route::get('/manajemen/produk/create', [ProdukController::class, 'create'])->name('manajemen.manajemen_produk_create');
    Route::post('/manajemen/produk', [ProdukController::class, 'store'])->name('manajemen.manajemen_produk_store');
    Route::get('/manajemen/produk/{id}/edit', [ProdukController::class, 'edit'])->name('manajemen.manajemen_produk_edit');
    Route::put('/manajemen/produk/{id}', [ProdukController::class, 'update'])->name('manajemen.manajemen_produk_update');
    Route::get('/manajemen/produk/{id}/delete', [ProdukController::class, 'destroy'])->name('manajemen.manajemen_produk_destroy');

    // 🧾 Manajemen Penjualan (dengan penamaan konsisten)
    Route::get('/manajemen/penjualan', [PenjualanController::class, 'index'])->name('manajemen.manajemen_penjualan');
    Route::get('/manajemen/penjualan/create', [PenjualanController::class, 'create'])->name('manajemen.manajemen_penjualan_create');
    Route::post('/manajemen/penjualan', [PenjualanController::class, 'store'])->name('manajemen.manajemen_penjualan_store');
    Route::get('/manajemen/penjualan/{id}', [PenjualanController::class, 'show'])->name('manajemen.manajemen_penjualan_show');
    Route::get('/manajemen/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('manajemen.manajemen_penjualan_edit');
    Route::put('/manajemen/penjualan/{id}', [PenjualanController::class, 'update'])->name('manajemen.manajemen_penjualan_update');
    Route::delete('/manajemen/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('manajemen.manajemen_penjualan_destroy');

    Route::get('/manajemen/pelanggan', [PelangganController::class, 'index'])->name('manajemen.manajemen_pelanggan');
    Route::get('/manajemen/pelanggan/create', [PelangganController::class, 'create'])->name('manajemen.manajemen_pelanggan_create');
    Route::post('/manajemen/pelanggan', [PelangganController::class, 'store'])->name('manajemen.manajemen_pelanggan_store');
    Route::get('/manajemen/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('manajemen.manajemen_pelanggan_edit');
    Route::put('/manajemen/pelanggan/{id}', [PelangganController::class, 'update'])->name('manajemen.manajemen_pelanggan_update');
    Route::delete('/manajemen/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('manajemen.manajemen_pelanggan_destroy');

    // 📄 Laporan Penjualan
    Route::get('/laporan/faktur', [LaporanController::class, 'index'])->name('laporan.faktur_laporan');
    Route::get('/laporan/faktur/{id}', [LaporanController::class, 'show'])->name('laporan.faktur_laporan_show');

    

});
