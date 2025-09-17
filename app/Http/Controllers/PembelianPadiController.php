<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianPadiController extends Controller
{
    public function index()
    {
        $data = DB::table('pembelian_padi')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        return response()->json($data);
    }
}
