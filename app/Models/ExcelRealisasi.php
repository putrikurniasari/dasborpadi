<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelRealisasi extends Model
{
    use HasFactory;

    protected $table = 'excel_realisasi';

    protected $fillable = [
        'tanggal_input',
        'file_excel',
    ];
}
