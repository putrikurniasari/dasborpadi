<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExcelTransaksi;
use App\Models\ExcelRealisasi;

class ExcelController extends Controller
{
    public function realisasiPadiUmkm()
    {
        $files = ExcelRealisasi::orderBy('created_at', 'desc')->get();
        return view('excel.realisasi_umkm', compact('files'));
    }

    public function pembelianPadi()
    {
        $files = ExcelTransaksi::orderBy('created_at', 'desc')->get();
        return view('excel.pembelian_padi', compact('files'));
    }

    public function uploadRealisasi(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $path = $request->file('file')->store('excel/realisasi', 'public');

        ExcelRealisasi::create([
            'tanggal_input' => now(),
            'file_excel' => $path,
        ]);

        return back()->with('success', 'File berhasil diupload!');
    }

    public function uploadPembelian(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $path = $request->file('file')->store('excel/pembelian', 'public');

        ExcelRealisasi::create([
            'tanggal_input' => now(),
            'file_excel' => $path,
        ]);

        return back()->with('success', 'File berhasil diupload!');
    }
}
