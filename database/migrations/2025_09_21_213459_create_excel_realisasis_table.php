<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_realisasi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');      // tanggal input
            $table->string('file_excel');       // path/nama file excel
            $table->integer('bulan');      
            $table->integer('tahun');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_realisasis');
    }
};
