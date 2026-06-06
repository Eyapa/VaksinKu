<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeluargaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_keluarga_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('keluarga.index'));

        $response->assertStatus(200);
        $response->assertViewIs('keluarga.index');
    }

    public function test_user_can_store_new_anggota_keluarga()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('keluarga.store'), [
            'nama' => 'John Doe',
            'nik' => '1234567890123456',
            'hubungan' => 'kepala_keluarga',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $response->assertRedirect(route('keluarga.index'));
        $this->assertDatabaseHas('anggota_keluargas', [
            'user_id' => $user->id,
            'nama' => 'John Doe',
            'nik' => '1234567890123456',
        ]);
    }

    public function test_user_cannot_update_other_users_anggota_keluarga()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $anggota = AnggotaKeluarga::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->put(route('keluarga.update', $anggota), [
            'nama' => 'Hacker Name',
            'nik' => '1234567890123456',
            'hubungan' => 'anak',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $response->assertStatus(403);
    }
}
