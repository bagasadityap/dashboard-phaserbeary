<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PemesananGedungTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_pemesanan_gedung_jalur_1(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $file = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('home.store-pesanan-gedung'), [
            'judul' => 'Judul Acara',
            'tanggal' => '2025-01-01',
            'jumlahPeserta' => 100,
            'noHP' => '081234567890',
            'suratPermohonanAcara' => $file,
            'deskripsiAcara' => 'Contoh deskripsi',
            'catatan' => 'Contoh catatan',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pesanan_gedungs', [
            'judul' => 'Judul Acara',
            'userId' => $user->id,
        ]);
    }

    public function test_pemesanan_gedung_jalur_2(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $response = $this->actingAs($user)->post(route('home.store-pesanan-gedung'), [
            'judul' => '',
            'tanggal' => '',
            'jumlahPeserta' => '',
            'noHP' => '',
            'suratPermohonanAcara' => null,
            'deskripsiAcara' => '',
            'catatan' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan.');
    }
}
