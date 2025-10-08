<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

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
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file_excel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        // === CEK apakah data dengan bulan & tahun ini sudah ada ===
        $sudahAda = DB::table('excel_transaksi')
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

        // === Simpan metadata ke tabel excel_transaksi ===
        $excelId = DB::table('excel_transaksi')->insertGetId([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_input' => now()->toDateString(),
            'file_excel' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === Baca file Excel ===
        $spreadsheet = IOFactory::load(storage_path('app/public/' . $path));
        $sheet = $spreadsheet->getSheetByName('Fix');

        if (!$sheet) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Hapus data Pembelian padi (kalau ada yang terkait)
            DB::table('pembelian_padi')->where('excel_id', $excelId)->delete();

            // Hapus metadata excel_realisasi
            DB::table('excel_realisasi')->where('id', $excelId)->delete();

            return redirect()->back()->with('error', 'File Excel yang diunggah tidak sesuai dengan template.
            Pastikan data di sheet “Fix” sesuai format template yang telah ditentukan.');
        }

        // === Loop data baris 3 sampai 36 ===
        for ($row = 3; ; $row++) {
            $kode = $sheet->getCell('A' . $row)->getValue();
            $deskripsi = $sheet->getCell('B' . $row)->getValue();

            // Hentikan loop jika kolom B berisi "TOTAL" (case-insensitive)
            if (strtoupper(trim($deskripsi)) === 'TOTAL') {
                break;
            }

            // Lewati baris jika kolom kode dan deskripsi kosong
            if (empty($kode) && empty($deskripsi)) {
                continue;
            }

            // Ambil nilai dan ubah kosong jadi null
            $plafond_opl = $sheet->getCell('C' . $row)->getCalculatedValue() ?: null;
            $transaksi_local = $sheet->getCell('D' . $row)->getCalculatedValue() ?: null;
            $transaksi_padi = $sheet->getCell('E' . $row)->getCalculatedValue() ?: null;
            $transaksi_padi_sd = $sheet->getCell('F' . $row)->getCalculatedValue() ?: null;
            $persen_terhadap_plafond = $sheet->getCell('G' . $row)->getCalculatedValue() ?: null;
            $status_user = $sheet->getCell('H' . $row)->getValue() ?: null;

            DB::table('pembelian_padi')->insert([
                'excel_id' => $excelId,
                'kode' => $kode ?: null,
                'deskripsi' => $deskripsi ?: null,
                'plafond_opl' => $plafond_opl,
                'transaksi_local' => $transaksi_local,
                'transaksi_padi' => $transaksi_padi,
                'transaksi_padi_sd' => $transaksi_padi_sd,
                'persen_terhadap_plafond' => $persen_terhadap_plafond,
                'status_user' => $status_user,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return redirect()->back()->with('success', 'File berhasil diupload dan data berhasil dimasukkan!');
    }

    public function destroy($id)
    {
        // Ambil data file dari database
        $file = DB::table('excel_transaksi')->where('id', $id)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        // Hapus file dari storage jika masih ada
        if (Storage::disk('public')->exists($file->file_excel)) {
            Storage::disk('public')->delete($file->file_excel);
        }

        // Hapus semua data pembelian_padi berdasarkan bulan & tahun file Excel
        DB::table('pembelian_padi')
            ->where('bulan', $file->bulan)
            ->where('tahun', $file->tahun)
            ->delete();

        // Hapus metadata file Excel dari tabel excel_transaksi
        DB::table('excel_transaksi')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'File dan data pembelian_padi berhasil dihapus!');
    }
}
