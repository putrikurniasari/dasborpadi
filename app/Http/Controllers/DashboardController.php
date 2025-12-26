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
            'tahunListPembelian' => $tahunList,
            'page' => 'pgdashboard'
        ]);
    }

    public function dashboardgrafik(Request $request)
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
        // Total seluruh realisasi_bulan
        $totalRealisasi = DB::table('realisasi_padi_umkm')->sum('realisasi_bulan');

        return view('dashboardgrafik', [
            'user' => $user,
            'title' => 'Dashboard',
            'dataRealisasi' => $dataRealisasi,
            'dataPembelian' => $dataPembelian,
            'tahunList' => $tahunList,
            'tahunListPembelian' => $tahunList,
            'totalRealisasi' => $totalRealisasi,
            'page' => 'pgdashboardgrafik'
        ]);
    }

    public function getRealisasiByYear($tahun)
    {
        $total = DB::table('realisasi_padi_umkm')
            ->where('tahun', $tahun)
            ->sum('realisasi_bulan');

        $target = DB::table('realisasi_padi_umkm')
            ->where('tahun', $tahun)
            ->value('target_tahun');

        return response()->json([
            'total' => $total,
            'target' => $target,
            'chart' => DB::table('realisasi_padi_umkm')
                ->where('tahun', $tahun)
                ->pluck('realisasi_bulan')
        ]);
    }

    public function getRealisasiTahun($tahun)
    {
        $data = DB::table('realisasi_padi_umkm')
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        return response()->json([
            'kategori' => $data->pluck('bulan')->map(fn($b) => $b ?? '-'),
            'realisasi' => $data->pluck('realisasi_sd_bulan')->map(fn($r) => $r ?? 0),
            'target' => $data->pluck('target_sd_bulan')->map(fn($t) => $t ?? 0),
        ]);

    }
    public function getRealisasiBulan($tahun, $bulan)
    {
        $data = DB::table('realisasi_padi_umkm')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        return response()->json([
            'kategori' => [$data->bulan ?? '-'],
            'realisasi' => [$data->realisasi_bulan ?? 0],
            'realisasi_sd_bulan' => [$data->realisasi_sd_bulan ?? 0],
            'target' => [$data->target_bulan ?? 0],
        ]);
    }

    public function ajaxRealisasiAllYear()
    {
        // Ambil data total per tahun (sum dari semua bulan)
        $data = DB::table('realisasi_padi_umkm')
            ->select('tahun', DB::raw('SUM(realisasi_bulan) as realisasi'), 'target_tahun')
            ->groupBy('tahun', 'target_tahun')
            ->orderBy('tahun')
            ->get();

        return response()->json([
            'kategori' => $data->pluck('tahun'),       // ["2023","2024","2025"]
            'realisasi' => $data->pluck('realisasi'),  // total realisasi per tahun
            'target' => $data->pluck('target_tahun')   // target tiap tahun (tidak dijumlah)
        ]);

    }



public function ajaxRealisasi(Request $request)
{
    $tahun = $request->query('tahun'); // ambil dari URL: /ajax/realisasi?tahun=2024

    $query = DB::table('realisasi_padi_umkm')
        ->orderBy('bulan');

    if ($tahun) {
        $query->where('tahun', $tahun);
    }

    return response()->json($query->get());
}


    public function realisasiBulanan($tahun)
{
    // Contoh data — sesuaikan dgn tabel Anda
    $bulan = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    // ambil data dari DB sesuai tahun
    $rows = DB::table('realisasi_padi_umkm')
        ->where('tahun', $tahun)
        ->orderBy('bulan')
        ->pluck('realisasi_bulan') // ganti sesuai field realisasi Anda
        ->toArray();

    // Format sesuai kebutuhan Highcharts
    $chart = [];
    foreach ($rows as $i => $val) {
        $chart[] = [
            "name" => $bulan[$i] ?? ("Bulan " . ($i+1)),
            "value" => $val,
        ];
    }

    return response()->json([
        'total' => array_sum($rows),
        'chart' => $chart
    ]);
}


    public function ajaxPembelian(Request $request)
    {
        $perPage = $request->per_page ?? 10;  // default 10

        $query = DB::table('pembelian_padi')->orderBy('bulan');

        if ($request->filter_tahun) {
            $query->where('tahun', $request->filter_tahun);
        }

        if ($request->filter_bulan) {
            $query->where('bulan', $request->filter_bulan);
        }

        return response()->json($query->paginate($perPage));
    }

public function ajaxPembelianChart(Request $request)
{
    $tahun = $request->tahun;   // ← PERBAIKAN

    $data = DB::table('pembelian_padi')
        ->select('bulan', DB::raw('SUM(transaksi_padi) as total'))
        ->when($tahun, fn($q) => $q->where('tahun', $tahun))
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    return response()->json($data);
}


    public function ajaxPembelianChartDetail(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $data = DB::table('pembelian_padi')
            ->select('deskripsi', 'transaksi_padi')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->orderBy('deskripsi')
            ->get();

        return response()->json($data);
    }


    
}
