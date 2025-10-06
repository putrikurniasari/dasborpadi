<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RealisasiPadiUmkmController;
use App\Http\Controllers\PembelianPadiController;
use App\Http\Controllers\ExcelController;

/*
|--------------------------------------------------------------------------
| Home & Auth
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::get('/home', fn() => redirect()->route('dashboard'))->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/transaksi-padi', [PembelianPadiController::class, 'getByBulan']);
Route::delete('/realisasi/{id}', [RealisasiPadiUmkmController::class, 'destroy'])->name('delete.realisasi');
Route::delete('/transaksi/{id}', [PembelianPadiController::class, 'destroy'])->name('delete.transaksi');


// Password reset
Route::get('/password/reset', fn() => view('auth.passwords.email'))->name('password.request');
Route::post('/password/email', fn() => back()->with('status', 'Link reset password telah dikirim (dummy).'))
    ->name('password.email');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::prefix('profile')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [ProfileController::class, 'password'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| API Chart Data
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::get('/realisasi-padi-umkm', [RealisasiPadiUmkmController::class, 'index']);
    Route::get('/pembelian-padi', [PembelianPadiController::class, 'index']);
    Route::post('/upload-realisasi', [RealisasiPadiUmkmController::class, 'uploadRealisasi'])->name('upload.realisasi');
    Route::post('/upload-pembelian', [PembelianPadiController::class, 'uploadPembelian'])->name('upload.pembelian');
});

/*
|--------------------------------------------------------------------------
| Excel Upload & View
|--------------------------------------------------------------------------
*/
Route::prefix('excel')->group(function () {
    // Halaman upload & list file
    Route::get('/realisasi-umkm', [ExcelController::class, 'realisasiPadiUmkm'])->name('excel.realisasi_umkm');
    Route::get('/pembelian-padi', [ExcelController::class, 'pembelianPadi'])->name('excel.pembelian_padi');

    // Proses upload file
    Route::post('/realisasi-umkm/upload', [ExcelController::class, 'uploadRealisasi'])->name('excel.realisasi.upload');
    Route::post('/pembelian-padi/upload', [ExcelController::class, 'uploadPembelian'])->name('excel.pembelian.upload');
});
