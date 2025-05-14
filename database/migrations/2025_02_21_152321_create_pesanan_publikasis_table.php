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
            $table->string('noHP');
            $table->string('suratPermohonanAcara');
            $table->string('dokumenOpsional')->nullable();
            $table->string('buktiPembayaran')->nullable();
            $table->string('posterAcara')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('hargaPublikasi', 15, 2)->nullable();
            $table->decimal('PPN', 15, 2)->nullable();
            $table->decimal('totalHarga', 15, 2)->nullable();
            $table->boolean('isConfirmed')->default(0);
            $table->boolean('isPaid')->default(0);
            $table->string('invoice')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('userId')->references('id')->on('users');
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
