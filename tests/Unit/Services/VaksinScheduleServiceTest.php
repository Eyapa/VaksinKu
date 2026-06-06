<?php

namespace Tests\Unit\Services;

use App\Models\AnggotaKeluarga;
use App\Models\User;
use App\Models\Vaksin;
use App\Services\VaksinScheduleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaksinScheduleServiceTest extends TestCase
{
    use RefreshDatabase;

    private VaksinScheduleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VaksinScheduleService();
    }

    public function test_bayi_mendapat_vaksin_bcg_sebagai_target(): void
    {
        $user = User::factory()->create();
        
        $bayi = AnggotaKeluarga::factory()->create([
            'user_id' => $user->id,
            'tanggal_lahir' => now()->subMonths(2)->format('Y-m-d'), // 2 bulan
        ]);

        $bcg = Vaksin::factory()->create([
            'kode' => 'BCG',
            'usia_minimal_bulan' => 0,
            'usia_maksimal_tahun' => 1,
        ]);

        $target = $this->service->getVaksinTargetUntukUsia($bayi);

        $this->assertTrue($target->contains('id', $bcg->id));
    }

    public function test_hitung_cakupan_keluarga_kosong_adalah_nol(): void
    {
        $user = User::factory()->create();

        $cakupan = $this->service->hitungCakupanKeluarga($user);

        $this->assertEquals(0.0, $cakupan);
    }
}
