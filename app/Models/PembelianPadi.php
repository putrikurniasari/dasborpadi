<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianPadi extends Model
{
    use HasFactory;

    protected $table = 'pembelian_padi';

    protected $fillable = [
        'excel_id',
        'kode',
        'deskripsi',
        'plafond_opl',
        'transaksi_local',
        'transaksi_padi',
        'transaksi_padi_sd',
        'persen_terhadap_plafond',
        'status_user',
        'bulan',
        'tahun',
    ];

    public $timestamps = true; // karena migration ada timestamps()
}
