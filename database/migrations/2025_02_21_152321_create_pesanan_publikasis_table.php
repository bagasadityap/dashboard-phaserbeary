<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal');
            $table->string('no_hp');
            $table->string('surat_permohonan_acara');
            $table->string('dokumen_opsional')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('poster_acara')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('total_biaya')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->string('invoice')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_publikasis');
    }
};
