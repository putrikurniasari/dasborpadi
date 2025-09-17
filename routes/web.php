<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Home route
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Route untuk edit profil
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

// Auth routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk reset password
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// Route untuk kirim link reset password
Route::post('/password/email', function () {
    // Implementasi pengiriman link reset password bisa ditambahkan di sini
    return back()->with('status', 'Link reset password telah dikirim (dummy).');
})->name('password.email');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// API untuk data chart realisasi_padi_umkm
Route::get('/api/realisasi-padi-umkm', [\App\Http\Controllers\RealisasiPadiUmkmController::class, 'index']);

// API untuk data chart pembelian_padi
Route::get('/api/pembelian-padi', [\App\Http\Controllers\PembelianPadiController::class, 'index']);
