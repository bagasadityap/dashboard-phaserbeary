<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TambahDataGedungTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_tambah_data_gedung_jalur_1(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $gambarvr = UploadedFile::fake()->create('surat.jpeg');

        $response = $this->actingAs($user)->post(route('gedung.store'), [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'gambar' => null,
            'gambarVR' => $gambarvr,
            'fasilitas' => null,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('gedungs', [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'fasilitas' => null,
        ]);
    }

    public function test_tambah_data_gedung_jalur_2(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $gambar = UploadedFile::fake()->image('gambar.png');
        $gambarvr = UploadedFile::fake()->image('gambarvr.jpeg');

        $response = $this->actingAs($user)->post(route('gedung.store'), [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'gambar' => [$gambar],
            'gambarVR' => $gambarvr,
            'fasilitas' => null,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('gedungs', [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'fasilitas' => null,
        ]);
    }

    public function test_tambah_data_gedung_jalur_3(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $gambarvr = UploadedFile::fake()->create('gamabarvr.jpeg');

        $response = $this->actingAs($user)->post(route('gedung.store'), [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'gambar' => null,
            'gambarVR' => $gambarvr,
            'fasilitas' => json_encode(['AC', 'Proyektor']),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('gedungs', [
            'nama' => 'Gedung A',
            'lokasi' => 'Jl. Veteran',
            'kapasitas' => 1000,
            'harga' => 25000000,
            'deskripsi' => 'Contoh deskripsi',
            'fasilitas' => ['AC', 'Proyektor'],
        ]);
    }

    public function test_tambah_data_gedung_jalur_4(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $response = $this->actingAs($user)->post(route('gedung.store'), [
            'nama' => 'Gedung A',
            'lokasi' => '',
            'kapasitas' => ' ',
            'harga' => '',
            'deskripsi' => '',
            'gambar' => null,
            'gambarVR' => null,
            'fasilitas' => null,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan.');
    }
}
