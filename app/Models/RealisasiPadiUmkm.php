<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiPadiUmkm extends Model
{
    use HasFactory;

    protected $table = 'realisasi_padi_umkm';

    protected $fillable = [
        'excel_id',
        'perusahaan',
        'tahun',
        'bulan',
        'target_tahun',
        'target_sd_bulan',
        'realisasi_sd_bulan',
        'sisa_target',
        'selisih_rp',
        'persentase_capaian',
    ];

    public $timestamps = true; // tabel ini cuma ada created_at
}
