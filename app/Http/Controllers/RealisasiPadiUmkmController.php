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

    // Endpoint untuk mengambil transaksi per kebun berdasarkan bulan & tahun
    public function getByBulan(Request $request)
    {
        $bulan = $request->bulan; // 1-12
        $tahun = $request->tahun; // 2025, dsb
        $kebun = $request->kebun ?? null; // optional filter kebun

        $query = DB::table('pembelian_padi')
            ->select('deskripsi as kebun', 'plafond_opl', 'transaksi_padi')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if($kebun){
            $query->where('deskripsi', $kebun);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function uploadRealisasi(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        // Simpan file ke storage/app/public/excel
        $path = $request->file('file_excel')->store('excel', 'public');

        // Simpan metadata ke tabel excel_transaksi
        DB::table('excel_realisasi')->insert([
            'tanggal_input' => now()->toDateString(),
            'file_excel'    => $path,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diupload!');
    }
}
