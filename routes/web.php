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
| AUTH (BEBAS DIAKSES)
|--------------------------------------------------------------------------
*/

// Halaman auth
Route::get('/auth', function () {
    return view('auth.auth', ['title' => 'Padi | Login & Register']);
})->name('auth');

// Login & Register
Route::post('/auth', [LoginController::class, 'login'])->name('login');
Route::post('/check-login', [LoginController::class, 'checkLogin'])->name('check.login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/check-username', [RegisterController::class, 'checkUsername'])->name('check.username');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE WAJIB LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/home', fn() => redirect()->route('dashboard'))->name('home');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboardgrafik', [DashboardController::class, 'dashboardgrafik'])->name('dashboard.grafik');

    // AJAX
    Route::get('/ajax/realisasi', [DashboardController::class, 'ajaxRealisasi']);
    Route::get('/ajax/pembelian', [DashboardController::class, 'ajaxPembelian']);
    Route::get('/ajax/pembelianchart', [DashboardController::class, 'ajaxPembelianChart']);
    Route::get('/ajax/pembelianchart-detail', [DashboardController::class, 'ajaxPembelianChartDetail']);
    Route::get('/ajax/realisasi/{tahun}', [DashboardController::class, 'getRealisasiByYear']);
    Route::get('/ajax/realisasi-tahun/{tahun}', [DashboardController::class, 'getRealisasiTahun']);
    Route::get('/ajax/realisasi-all-year', [DashboardController::class, 'ajaxRealisasiAllYear']);
    Route::get('/ajax/realisasi-bulan/{tahun}/{bulan}', [DashboardController::class, 'getRealisasiBulan']);
    Route::get('/ajax/realisasi-bulanan/{tahun}', [DashboardController::class, 'realisasiBulanan']);

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Transaksi & Realisasi
    Route::get('/transaksi-padi', [PembelianPadiController::class, 'getByBulan']);
    Route::delete('/realisasi/{id}', [RealisasiPadiUmkmController::class, 'destroy'])->name('delete.realisasi');
    Route::delete('/transaksi/{id}', [PembelianPadiController::class, 'destroy'])->name('delete.transaksi');

    // API
    Route::prefix('api')->group(function () {
        Route::get('/realisasi-padi-umkm', [RealisasiPadiUmkmController::class, 'index']);
        Route::get('/pembelian-padi', [PembelianPadiController::class, 'index']);
        Route::post('/upload-realisasi', [RealisasiPadiUmkmController::class, 'uploadRealisasi'])->name('upload.realisasi');
        Route::post('/upload-pembelian', [PembelianPadiController::class, 'uploadPembelian'])->name('upload.pembelian');
    });

    // Excel
    Route::prefix('excel')->group(function () {
        Route::get('/realisasi-umkm', [ExcelController::class, 'realisasiPadiUmkm'])->name('excel.realisasi_umkm');
        Route::get('/pembelian-padi', [ExcelController::class, 'pembelianPadi'])->name('excel.pembelian_padi');
        Route::post('/realisasi-umkm/upload', [ExcelController::class, 'uploadRealisasi'])->name('excel.realisasi.upload');
        Route::post('/pembelian-padi/upload', [ExcelController::class, 'uploadPembelian'])->name('excel.pembelian.upload');
    });

});
