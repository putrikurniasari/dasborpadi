<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('realisasi_padi_umkm', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->id();
            $table->unsignedBigInteger('excel_id');
            $table->string('perusahaan', 100);
            $table->integer('tahun');
            $table->tinyInteger('bulan');
            $table->bigInteger('target_tahun')->nullable();
            $table->bigInteger('target_sd_bulan')->nullable();
            $table->bigInteger('realisasi_sd_bulan')->nullable();
            $table->bigInteger('sisa_target')->nullable();
            $table->bigInteger('selisih_rp')->nullable();
            $table->decimal('persentase_capaian', 6, 2)->nullable();
            $table->timestamps();

            // ✅ Tambahkan foreign key
            $table->foreign('excel_id')
                ->references('id')
                ->on('excel_realisasi')
                ->onDelete('cascade'); // biar kalau excel_transaksi dihapus, data ini ikut hilang
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi_padi_umkm');
    }
};
