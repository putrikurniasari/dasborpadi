<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelTransaksi extends Model
{
    use HasFactory;

    protected $table = 'excel_transaksi';

    protected $fillable = [
        'tanggal_input',
        'file_excel',
    ];
}
