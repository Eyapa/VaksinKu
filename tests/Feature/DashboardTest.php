<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_dashboard_renders_successfully(): void
    {
        $user = \App\Models\User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('Status Vaksinasi');
        $response->assertSee('Anggota Keluarga');
    }

    public function test_dashboard_shows_active_pengumuman(): void
    {
        $user = \App\Models\User::factory()->create();
        $pengumuman = \App\Models\Pengumuman::create([
            'judul' => 'Test Pengumuman',
            'konten' => 'Isi pengumuman test',
            'is_aktif' => true,
        ]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('Test Pengumuman');
    }
}
