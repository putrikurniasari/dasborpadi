<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil list tahun unik
        $tahunList = DB::table('realisasi_padi_umkm')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        $tahunListPembelian = DB::table('pembelian_padi')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        // Query dasar
        $dataRealisasi = DB::table('realisasi_padi_umkm');
        $dataPembelian = DB::table('pembelian_padi');

        $dataRealisasi = $dataRealisasi->paginate(12);
        $dataPembelian = $dataPembelian->paginate(40);

        return view('dashboard', [
            'user' => $user,
            'title' => 'Dashboard',
            'dataRealisasi' => $dataRealisasi,
            'dataPembelian' => $dataPembelian,
            'tahunList' => $tahunList,
            'tahunListPembelian' => $tahunList
        ]);
    }

    public function ajaxRealisasi(Request $request)
    {
        $tahun = $request->tahun;

        $query = DB::table('realisasi_padi_umkm')
            ->orderBy('tahun')
            ->orderBy('bulan');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        return response()->json($query->get());
    }

    public function ajaxPembelian(Request $request)
    {
        $tahun = $request->filter_tahun;
        $bulan = $request->filter_bulan;

        $query = DB::table('pembelian_padi')->orderBy('bulan');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        return response()->json($query->get());
    }



}
