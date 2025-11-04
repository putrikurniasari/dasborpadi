<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

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

    public function uploadRealisasi(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file_excel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        // === CEK apakah data dengan bulan & tahun ini sudah ada ===
        $sudahAda = DB::table('excel_realisasi')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Data untuk bulan dan tahun ini sudah ada. Silakan hapus data lama terlebih dahulu sebelum upload kembali.');
        }

        // === Simpan file ke storage ===
        $file = $request->file('file_excel');
        $originalName = $file->getClientOriginalName();
        $cleanName = preg_replace('/\s+/', '_', $originalName);
        $path = $file->storeAs('excel', $cleanName, 'public');

        // === Simpan metadata file ke tabel excel_realisasi ===
        $excelId = DB::table('excel_realisasi')->insertGetId([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_input' => now()->toDateString(),
            'file_excel' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === Baca file Excel ===
        $spreadsheet = IOFactory::load(storage_path('app/public/' . $path));

        // Gunakan sheet "KERTAS KERJA"
        $sheet = $spreadsheet->getSheetByName('KERTAS KERJA');

        if (!$sheet) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Hapus data realisasi_padi_umkm (kalau ada yang terkait)
            DB::table('realisasi_padi_umkm')->where('excel_id', $excelId)->delete();

            // Hapus metadata excel_realisasi
            DB::table('excel_realisasi')->where('id', $excelId)->delete();

            return redirect()->back()->with('error', 'File Excel yang diunggah tidak sesuai dengan template.
            Pastikan Anda menggunakan sheet “KERTAS KERJA” dan mengisi data sesuai format template yang telah ditentukan.');
        }

        // Ambil data dari sel
        $perusahaan = $sheet->getCell('C6')->getCalculatedValue();
        if ($request->bulan == 1) {
            $target_tahun = $sheet->getCell('D6')->getCalculatedValue();
        }else{
            $target_tahun = $sheet->getCell('F6')->getCalculatedValue();
        }
        $target_bulan = $target_tahun / 12;
        $target_sd_bulan = $sheet->getCell('G6')->getCalculatedValue();
        $realisasi_sd_bulan = $sheet->getCell('H6')->getCalculatedValue();
        
        if ($request->bulan == 1) {
            $realisasi_bulan =$realisasi_sd_bulan;    
        }else{
            $realisasi_bulan = null;
        }
        $sisa_target = $sheet->getCell('I6')->getCalculatedValue();
        $selisih_bulan = $realisasi_bulan - (float) $target_bulan;
        $selisih_sd_bulan = $sheet->getCell('J6')->getCalculatedValue();
        $persentase_capaian = $sheet->getCell('K6')->getCalculatedValue();


        // === Simpan data ke tabel realisasi_padi_umkm ===
        DB::table('realisasi_padi_umkm')->insert([
            'excel_id' => $excelId, // tambahkan relasi agar tahu asal file-nya
            'perusahaan' => $perusahaan,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'target_tahun' => $target_tahun,
            'target_bulan' => $target_bulan,
            'target_sd_bulan' => $target_sd_bulan,
            'realisasi_bulan' => $realisasi_bulan,
            'realisasi_sd_bulan' => $realisasi_sd_bulan,
            'sisa_target' => $sisa_target,
            'selisih_bulan' => $selisih_bulan,
            'selisih_sd_bulan' => $selisih_sd_bulan,
            'persentase_capaian' => $persentase_capaian,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diupload dan data tersimpan!');
    }

    public function destroy($id)
    {
        // Ambil data file dari database
        $file = DB::table('excel_realisasi')->where('id', $id)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        // Hapus file dari storage
        if (Storage::disk('public')->exists($file->file_excel)) {
            Storage::disk('public')->delete($file->file_excel);
        }

        // Hapus data realisasi_padi_umkm yang terkait
        DB::table('realisasi_padi_umkm')
            ->where('excel_id', $id)
            ->delete();

        // Hapus metadata file
        DB::table('excel_realisasi')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'File dan data realisasi berhasil dihapus!');
    }

}
