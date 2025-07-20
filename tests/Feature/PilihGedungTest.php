<?php

namespace Tests\Feature;

use App\Models\Gedung;
use App\Models\PesananGedung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PilihGedungTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_pilih_gedung_jalur_1(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $gambarvr = UploadedFile::fake()->create('gambarvr.jpeg');
        $file = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');

        $gedung = Gedung::create([
            'id' => 1,
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'gambarVR' => $gambarvr,
        ]);

        $pesanan = PesananGedung::create([
            'userId' => $user->id,
            'status' => 1,
            'judul' => 'Judul Acara',
            'tanggal' => '2025-01-01',
            'jumlahPeserta' => 100,
            'noHP' => '081234567890',
            'suratPermohonanAcara' => $file,
            'deskripsiAcara' => 'Contoh deskripsi',
            'catatan' => 'Contoh catatan',
        ]);

        $response = $this->actingAs($user)->get(route('home.pilih', [1, $pesanan->id]));

        $this->assertDatabaseHas('pesanan_gedungs', [
            'id' => $pesanan->id,
            'gedungId' => $gedung->id,
        ]);
    }

    public function test_pilih_gedung_jalur_2(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $gambarvr = UploadedFile::fake()->create('gambarvr.jpeg');
        $file = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');

        $pesanan = PesananGedung::create([
            'userId' => $user->id,
            'status' => 1,
            'judul' => 'Judul Acara',
            'tanggal' => '2025-01-01',
            'jumlahPeserta' => 100,
            'noHP' => '081234567890',
            'suratPermohonanAcara' => $file,
            'deskripsiAcara' => 'Contoh deskripsi',
            'catatan' => 'Contoh catatan',
        ]);

        $response = $this->actingAs($user)->get(route('home.pilih', [999999999, $pesanan->id]));

        $response->assertStatus(404);
    }
}
