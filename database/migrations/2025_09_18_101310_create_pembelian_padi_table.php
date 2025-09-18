<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembelian_padi', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('deskripsi');
            $table->bigInteger('plafond_opl')->nullable();
            $table->bigInteger('transaksi_local')->nullable();
            $table->bigInteger('transaksi_padi')->nullable();
            $table->bigInteger('transaksi_padi_sd')->nullable();
            $table->string('persen_terhadap_plafond');
            $table->string('status_user');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_padi');
    }
};
