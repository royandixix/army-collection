<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman utama diarahkan ke login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
