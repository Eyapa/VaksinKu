<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatRequest;
use App\Services\VaksinScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    public function __construct(
        private readonly VaksinScheduleService $scheduleService,
    ) {}

    public function index(): View
    {
        $user = Auth::user();
        $tab = request('tab', 'saya');
        $anggotaId = request('anggota_id');

        $semuaAnggota = collect();
        if ($user) {
            $semuaAnggota = $user->anggotaKeluargas()->get();
        }

        $anggotaKeluargaList = $semuaAnggota;
        $saya = null;
        
        if ($user && $user->nik) {
            $saya = $semuaAnggota->where('nik', $user->nik)->first();
            if ($saya) {
                $anggotaKeluargaList = $semuaAnggota->where('id', '!=', $saya->id)->values();
            }
        }

        $selectedAnggota = null;

        if ($tab === 'saya') {
            $selectedAnggota = $saya;
            $anggotaIds = $saya ? [$saya->id] : [];
        } else {
            // Tab Keluarga
            if ($anggotaId) {
                $selectedAnggota = $anggotaKeluargaList->firstWhere('id', $anggotaId);
            }
            
            if (!$selectedAnggota) {
                $selectedAnggota = $anggotaKeluargaList->first();
            }

            $anggotaIds = $selectedAnggota ? [$selectedAnggota->id] : [];
        }

        $jadwals = \App\Models\JadwalVaksin::with(['vaksin', 'faskes.vaksins', 'anggotaKeluarga'])
            ->whereIn('anggota_keluarga_id', $anggotaIds)
            ->get();

        $timeline = collect();

        foreach($jadwals as $j) {
            $isRiwayat = strtolower($j->status) === 'selesai' || strtolower($j->status) === 'batal';
            $timeline->push((object)[
                'id' => ($isRiwayat ? 'r_' : 'j_').$j->id,
                'type' => $isRiwayat ? 'riwayat' : 'jadwal',
                'model_id' => $j->id,
                'status' => ucfirst($j->status),
                'vaksin' => $j->vaksin,
                'faskes' => $j->faskes,
                'anggota' => $j->anggotaKeluarga,
                'tanggal' => $j->tanggal_jadwal,
                'jadwalModel' => $j, // to pass full data
                'file_sertifikat_url' => $j->file_sertifikat_url,
            ]);
        }

        $timeline = $timeline->sortByDesc('tanggal')->values();

        $totalSelesaiCount = collect($timeline)->where('status', 'Selesai')->count();
        $totalTimelineCount = collect($timeline)->count();
        
        $persentaseCakupan = $totalTimelineCount > 0 ? round(($totalSelesaiCount / $totalTimelineCount) * 100) : 0;
        
        $targetAnggota = $selectedAnggota ?: $saya;

        if (!$targetAnggota && $tab === 'saya') {
            $targetAnggota = (object)[
                'id' => null,
                'nama' => $user->name,
                'nik' => $user->nik,
                'status_vaksin' => 'Tidak Ada Jadwal',
                'status_color' => 'outline'
            ];
            
            if ($totalTimelineCount > 0) {
                if ($totalSelesaiCount > 0) {
                    $targetAnggota->status_vaksin = 'Selesai';
                    $targetAnggota->status_color = 'success-green';
                }
                $hasJadwal = collect($timeline)->where('type', 'jadwal')->count() > 0;
                if ($hasJadwal) {
                    $targetAnggota->status_vaksin = 'Dijadwalkan';
                    $targetAnggota->status_color = 'warning-orange';
                }
            }
        }

        if ($targetAnggota && isset($targetAnggota->id)) {
            $latestJadwal = \App\Models\JadwalVaksin::where('anggota_keluarga_id', $targetAnggota->id)
                ->whereIn('status', ['terdaftar', 'konfirmasi'])
                ->orderBy('tanggal_jadwal')
                ->first();
            $latestRiwayat = \App\Models\JadwalVaksin::where('anggota_keluarga_id', $targetAnggota->id)
                ->where('status', 'selesai')
                ->orderBy('tanggal_jadwal', 'desc')
                ->first();

            if ($latestJadwal) {
                $targetAnggota->status_vaksin = 'Dijadwalkan';
                $targetAnggota->status_color = 'warning-orange';
            } elseif ($latestRiwayat) {
                if (\Carbon\Carbon::parse($latestRiwayat->tanggal_jadwal)->diffInDays(now()) > 1) {
                    $targetAnggota->status_vaksin = 'Tidak Ada Jadwal';
                    $targetAnggota->status_color = 'outline';
                } else {
                    $targetAnggota->status_vaksin = 'Selesai';
                    $targetAnggota->status_color = 'success-green';
                }
            } else {
                $targetAnggota->status_vaksin = 'Tidak Ada Jadwal';
                $targetAnggota->status_color = 'outline';
            }
        }

        return view('riwayat.index', [
            'timeline' => $timeline, 
            'persentaseCakupan' => $persentaseCakupan,
            'totalRiwayat' => collect($timeline)->where('type', 'riwayat')->count(),
            'tab' => $tab,
            'anggotaId' => $anggotaId,
            'anggotaKeluargaList' => $anggotaKeluargaList,
            'selectedAnggota' => $selectedAnggota,
            'saya' => $saya,
            'targetAnggota' => $targetAnggota,
            'vaksins' => \App\Models\Vaksin::orderBy('nama')->get()
        ]);
    }

    public function store(StoreRiwayatRequest $request): RedirectResponse
    {
        $riwayat = $this->scheduleService->catatVaksin(
            $request->validated()
        );

        return redirect()
            ->route('riwayat.index')
            ->with('success', "Vaksin {$riwayat->vaksin->nama} berhasil dicatat.");
    }

    public function destroy(\App\Models\JadwalVaksin $riwayat): RedirectResponse
    {
        if ($riwayat->anggotaKeluarga->user_id !== Auth::id()) {
            abort(403);
        }

        if (strtolower($riwayat->status) === 'terdaftar' || strtolower($riwayat->status) === 'konfirmasi') {
            return back()->with('error', 'Status jadwal tidak dapat dihapus melalui riwayat.');
        }

        $riwayat->delete();

        return back()->with('success', 'Riwayat vaksin dihapus.');
    }
}
