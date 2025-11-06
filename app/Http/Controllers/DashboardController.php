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
    public function ajaxSearchRealisasi(Request $request)
    {
        $search = $request->search;

        $data = DB::table('realisasi_padi_umkm')
            ->where(function ($q) use ($search) {
                $q->where('tahun', 'like', "%$search%")
                    ->orWhere('target_bulan', 'like', "%$search%")
                    ->orWhere('realisasi_bulan', 'like', "%$search%")

                    // ✅ Cari angka bulan
                    ->orWhere('bulan', 'like', "%$search%")

                    // ✅ Cari nama bulan berdasarkan angka
                    ->orWhereRaw("
                    CASE bulan
                        WHEN 1 THEN 'januari'
                        WHEN 2 THEN 'februari'
                        WHEN 3 THEN 'maret'
                        WHEN 4 THEN 'april'
                        WHEN 5 THEN 'mei'
                        WHEN 6 THEN 'juni'
                        WHEN 7 THEN 'juli'
                        WHEN 8 THEN 'agustus'
                        WHEN 9 THEN 'september'
                        WHEN 10 THEN 'oktober'
                        WHEN 11 THEN 'november'
                        WHEN 12 THEN 'desember'
                    END LIKE ?
                ", ["%$search%"]);
            })
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return response()->json($data);
    }

    public function ajaxSearchPembelian(Request $request)
    {
        $search = $request->search;

        $data = DB::table('pembelian_padi')
            ->where(function ($q) use ($search) {
                $q->where('tahun', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")  // nama kebun
                    ->orWhere('transaksi_padi', 'like', "%$search%")
                    ->orWhere('transaksi_padi_sd', 'like', "%$search%")
                    ->orWhere('plafond_opl', 'like', "%$search%")
                    ->orWhereRaw("
                    CASE bulan
                        WHEN 1 THEN 'januari'
                        WHEN 2 THEN 'februari'
                        WHEN 3 THEN 'maret'
                        WHEN 4 THEN 'april'
                        WHEN 5 THEN 'mei'
                        WHEN 6 THEN 'juni'
                        WHEN 7 THEN 'juli'
                        WHEN 8 THEN 'agustus'
                        WHEN 9 THEN 'september'
                        WHEN 10 THEN 'oktober'
                        WHEN 11 THEN 'november'
                        WHEN 12 THEN 'desember'
                    END LIKE ?
              ", ["%$search%"]);
            })
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return response()->json($data);
    }

}
