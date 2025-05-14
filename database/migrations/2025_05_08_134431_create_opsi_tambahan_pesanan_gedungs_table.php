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
        Schema::create('opsi_tambahan_pesanan_gedungs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->decimal('harga', 15, 2);
            $table->foreignId('pesananId')->references('id')->on('pesanan_gedungs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_tambahan_pesanan_gedungs');
    }
};
