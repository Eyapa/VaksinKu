<?php
namespace App\Services;

use App\Models\AnggotaKeluarga;
use App\Models\JadwalVaksin;
use App\Models\RiwayatVaksin;
use App\Models\User;
use App\Models\Vaksin;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class VaksinScheduleService
{
    /**
     * Hitung persentase cakupan vaksinasi seluruh keluarga.
     */
    public function hitungCakupanKeluarga(User $user): float
    {
        $anggotaList = $user->anggotaKeluargas()
            ->with('riwayatVaksin')
            ->get();

        if ($anggotaList->isEmpty()) {
            return 0.0;
        }

        $totalTarget  = 0;
        $totalSelesai = 0;

        foreach ($anggotaList as $anggota) {
            $vaksinTarget = $this->getVaksinTargetUntukUsia($anggota);
            $totalTarget  += $vaksinTarget->count();
            $totalSelesai += $anggota->riwayatVaksin()
                ->whereIn('vaksin_id', $vaksinTarget->pluck('id'))
                ->where('status', 'selesai')
                ->count();
        }

        return $totalTarget > 0
            ? round(($totalSelesai / $totalTarget) * 100, 1)
            : 0.0;
    }

    /**
     * Hitung persentase cakupan vaksinasi untuk satu anggota.
     */
    public function hitungCakupanAnggota(AnggotaKeluarga $anggota): float
    {
        $vaksinTarget = $this->getVaksinTargetUntukUsia($anggota);
        $totalTarget = $vaksinTarget->count();
        
        if ($totalTarget === 0) return 0.0;

        $totalSelesai = $anggota->riwayatVaksin()
            ->whereIn('vaksin_id', $vaksinTarget->pluck('id'))
            ->where('status', 'selesai')
            ->count();

        return round(($totalSelesai / $totalTarget) * 100, 1);
    }

    /**
     * Ambil daftar vaksin yang seharusnya diterima berdasarkan usia.
     */
    public function getVaksinTargetUntukUsia(AnggotaKeluarga $anggota): Collection
    {
        $usiaBulan = Carbon::parse($anggota->tanggal_lahir)->diffInMonths(now());

        return Vaksin::where('usia_minimal_bulan', '<=', $usiaBulan)
            ->where(function ($q) use ($usiaBulan) {
                $q->whereNull('usia_maksimal_tahun')
                  ->orWhere('usia_maksimal_tahun', '>=', floor($usiaBulan / 12));
            })
            ->get();
    }

    /**
     * Catat vaksin baru dan generate nomor sertifikat.
     */
    public function catatVaksin(array $data): RiwayatVaksin
    {
        $data['nomor_sertifikat'] = $this->generateNomorSertifikat();
        $data['status']           = 'selesai';

        return RiwayatVaksin::create($data);
    }

    private function generateNomorSertifikat(): string
    {
        do {
            $nomor = 'VK-' . strtoupper(uniqid());
        } while (RiwayatVaksin::where('nomor_sertifikat', $nomor)->exists());

        return $nomor;
    }
}
