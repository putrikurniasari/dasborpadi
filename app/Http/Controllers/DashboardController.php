<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $dataRealisasi = DB::table('realisasi_padi_umkm')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->paginate(10);

    return view('dashboard', [
        'user' => $user,
        'title' => 'Dashboard',
        'dataRealisasi' => $dataRealisasi
    ]);
}

}
