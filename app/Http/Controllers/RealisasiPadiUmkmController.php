<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RealisasiPadiUmkmController extends Controller
{
    public function index()
    {
        $data = DB::table('realisasi_padi_umkm')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        return response()->json($data);
    }
}
