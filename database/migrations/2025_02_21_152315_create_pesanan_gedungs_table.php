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
            $table->string('no_hp');
            $table->string('dokumen');
            $table->text('catatan')->nullable();
            $table->foreignId('gedung_id')->references('id')->on('gedungs')->onDelete('set null');
            $table->foreignId('gedung_id')->references('id')->on('gedungs')->onDelete('set null');
            $table->integer('total_biaya');
            $table->boolean('is_paid')->default(0);
            $table->string('invoice');
            $table->timestamps();
        });

        Schema::create('gedung_pesanan', function (Blueprint $table) {
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
    }
};
