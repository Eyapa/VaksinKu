<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\JadwalVaksin;
use App\Models\RiwayatVaksin;
use App\Models\User;
use App\Models\Vaksin;
use App\Models\FasilitasKesehatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_riwayat_and_jadwal_timeline()
    {
        $user = User::factory()->create();
        
        $anggota = AnggotaKeluarga::factory()->create([
            'user_id' => $user->id,
        ]);

        $vaksin = Vaksin::factory()->create(['nama' => 'Vaksin A']);
        $faskes = FasilitasKesehatan::factory()->create();

        RiwayatVaksin::factory()->create([
            'anggota_keluarga_id' => $anggota->id,
            'vaksin_id' => $vaksin->id,
            'faskes_id' => $faskes->id,
            'status' => 'selesai',
            'tanggal_vaksin' => now()->subDays(5),
        ]);

        JadwalVaksin::factory()->create([
            'anggota_keluarga_id' => $anggota->id,
            'vaksin_id' => $vaksin->id,
            'faskes_id' => $faskes->id,
            'status' => 'terdaftar',
            'tanggal_jadwal' => now()->addDays(5),
        ]);

        $response = $this->actingAs($user)->get(route('riwayat.index'));

        $response->assertStatus(200);
        $response->assertViewHas('timeline');
        $this->assertCount(2, $response->viewData('timeline'));
    }
}
