<?php

namespace Tests\Feature\Riwayat;

use App\Models\AnggotaKeluarga;
use App\Models\RiwayatVaksin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatVaksinTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_riwayat_memerlukan_auth(): void
    {
        $this->get(route('riwayat.index'))
             ->assertRedirect(route('login'));
    }

    public function test_user_hanya_melihat_riwayat_keluarganya(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $anggota1 = AnggotaKeluarga::factory()->create(['user_id' => $user1->id]);
        $anggota2 = AnggotaKeluarga::factory()->create(['user_id' => $user2->id]);

        $vaksin1 = \App\Models\Vaksin::factory()->create(['nama' => 'Vaksin Khusus User Satu']);
        $vaksin2 = \App\Models\Vaksin::factory()->create(['nama' => 'Vaksin Khusus User Dua']);

        $riwayat1 = RiwayatVaksin::factory()->create([
            'anggota_keluarga_id' => $anggota1->id,
            'vaksin_id' => $vaksin1->id,
            'status' => 'selesai'
        ]);
        $riwayatLain = RiwayatVaksin::factory()->create([
            'anggota_keluarga_id' => $anggota2->id,
            'vaksin_id' => $vaksin2->id,
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($user1)
                         ->get(route('riwayat.index'))
                         ->assertOk();

        $response->assertSee($vaksin1->nama);
        $response->assertDontSee($vaksin2->nama);
    }

    public function test_user_tidak_dapat_hapus_riwayat_orang_lain(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $anggota2 = AnggotaKeluarga::factory()->create(['user_id' => $user2->id]);
        $riwayat = RiwayatVaksin::factory()->create(['anggota_keluarga_id' => $anggota2->id]);

        $this->actingAs($user1)
             ->delete(route('riwayat.destroy', $riwayat))
             ->assertForbidden();

        $this->assertDatabaseHas('riwayat_vaksins', ['id' => $riwayat->id]);
    }
}
