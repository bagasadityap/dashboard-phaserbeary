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
        Schema::create('pesanan_gedungs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal');
            $table->integer('jumlahPeserta');
            $table->string('noHP');
            $table->string('suratPermohonanAcara');
            $table->string('dokumenOpsional')->nullable();
            $table->string('buktiPembayaran')->nullable();
            $table->string('dataPartisipan')->nullable();
            $table->text('deskripsiAcara');
            $table->text('catatan')->nullable();
            $table->text('alasanPenolakan')->nullable();
            $table->decimal('hargaGedung', 15, 2)->nullable();
            $table->decimal('PPN', 15, 2)->nullable();
            $table->decimal('totalHarga', 15, 2)->nullable();
            $table->boolean('isConfirmed')->default(0);
            $table->boolean('isPaid')->default(0);
            $table->string('invoice')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('userId')->references('id')->on('users');
            $table->foreignId('gedungId')->nullable()->references('id')->on('gedungs')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('gedung_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_gedung_id')->constrained()->onDelete('cascade');
            $table->foreignId('gedung_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_gedungs');
        Schema::dropIfExists('gedung_pesanans');
    }
};
