<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function uploadPembelian(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        $file = $request->file('file_excel');

        // 1️⃣ Simpan file ke storage dulu
        $path = $file->store('excel', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // 2️⃣ Import data dari sheet "Fix" (baris 3 ke bawah)
        $collections = Excel::toCollection(null, $fullPath)->onlySheets('Fix');
        $rows = $collections->first(); // ambil sheet Fix

        foreach ($rows as $index => $row) {
            if ($index < 2) continue; // skip baris 1-2
            if (empty($row[0])) continue; // skip kalau kode kosong

            DB::table('pembelian_padi')->insert([
                'kode' => $row[0],
                'deskripsi' => $row[1],
                'plafond_opl' => is_numeric($row[2]) ? $row[2] : (int) str_replace(',', '', $row[2]),
                'transaksi_local' => ($row[3] == '-' ? 0 : (int) str_replace(',', '', $row[3])),
                'transaksi_padi' => (int) str_replace(',', '', $row[4]),
                'transaksi_padi_sd' => (int) str_replace(',', '', $row[5]),
                'persen_terhadap_plafond' => floatval(str_replace('%', '', $row[6])),
                'status_user' => $row[7],
                'bulan' => null,
                'tahun' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3️⃣ Simpan metadata ke tabel excel_transaksi
        DB::table('excel_transaksi')->insert([
            'tanggal_input' => now()->toDateString(),
            'file_excel' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data dari sheet Fix berhasil diimport ke tabel pembelian_padi!');
    }
}
