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

        // Simpan file ke storage/app/public/excel
        $file = $request->file('file_excel');
        $originalName = $file->getClientOriginalName();
        $cleanName = preg_replace('/\s+/', '_', $originalName);
        $path = $file->storeAs('excel', $cleanName, 'public');

        // Simpan metadata ke tabel excel_transaksi
        DB::table('excel_transaksi')->insert([
            'tanggal_input' => now()->toDateString(),
            'file_excel'    => $path,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diupload!');
    }
    public function destroy($id)
    {
        // Ambil data file dari database
        $file = DB::table('excel_transaksi')->where('id', $id)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        // Hapus file dari storage jika masih ada
        if (\Storage::disk('public')->exists($file->file_excel)) {
            \Storage::disk('public')->delete($file->file_excel);
        }

        // Hapus data dari database
        DB::table('excel_transaksi')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus!');
    }
}
