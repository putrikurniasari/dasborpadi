<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembelian_padi', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->id();
            $table->unsignedBigInteger('excel_id');
            $table->string('kode')->nullable();;
            $table->string('deskripsi');
            $table->bigInteger('plafond_opl')->nullable();
            $table->bigInteger('transaksi_local')->nullable();
            $table->bigInteger('transaksi_padi')->nullable();
            $table->bigInteger('transaksi_padi_sd')->nullable();
            $table->decimal('persen_terhadap_plafond', 5, 2)->nullable();
            $table->string('status_user');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->timestamps();

            // ✅ Tambahkan foreign key
            $table->foreign('excel_id')
                ->references('id')
                ->on('excel_transaksi')
                ->onDelete('cascade'); // biar kalau excel_transaksi dihapus, data ini ikut hilang
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_padi');
    }
};
