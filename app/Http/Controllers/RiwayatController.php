<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatRequest;
use App\Models\RiwayatVaksin;
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

        $riwayats = RiwayatVaksin::with(['vaksin', 'faskes', 'anggotaKeluarga'])
            ->whereIn('anggota_keluarga_id', $anggotaIds)
            ->get();

        $jadwals = \App\Models\JadwalVaksin::with(['vaksin', 'faskes', 'anggotaKeluarga'])
            ->whereIn('anggota_keluarga_id', $anggotaIds)
            ->get();

        $timeline = collect();

        foreach($riwayats as $r) {
            $status = ucfirst($r->status);
            if (strtolower($r->status) === 'pending') {
                $status = 'Memproses';
            } elseif (strtolower($r->status) === 'batal') {
                $status = 'Ditolak';
            }

            $timeline->push((object)[
                'id' => 'r_'.$r->id,
                'type' => 'riwayat',
                'model_id' => $r->id,
                'status' => $status,
                'vaksin' => $r->vaksin,
                'faskes' => $r->faskes,
                'anggota' => $r->anggotaKeluarga,
                'tanggal' => $r->tanggal_vaksin,
            ]);
        }

        foreach($jadwals as $j) {
            $timeline->push((object)[
                'id' => 'j_'.$j->id,
                'type' => 'jadwal',
                'status' => ucfirst($j->status),
                'vaksin' => $j->vaksin,
                'faskes' => $j->faskes,
                'anggota' => $j->anggotaKeluarga,
                'tanggal' => $j->tanggal_jadwal,
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
                ->where('tanggal_jadwal', '>=', now()->toDateString())
                ->orderBy('tanggal_jadwal')
                ->first();
            $latestRiwayat = \App\Models\RiwayatVaksin::where('anggota_keluarga_id', $targetAnggota->id)
                ->orderBy('tanggal_vaksin', 'desc')
                ->first();

            if ($latestJadwal) {
                $targetAnggota->status_vaksin = 'Dijadwalkan';
                $targetAnggota->status_color = 'warning-orange';
            } elseif ($latestRiwayat) {
                if (\Carbon\Carbon::parse($latestRiwayat->tanggal_vaksin)->diffInDays(now()) > 1) {
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
            'totalRiwayat' => $riwayats->count(),
            'tab' => $tab,
            'anggotaId' => $anggotaId,
            'anggotaKeluargaList' => $anggotaKeluargaList,
            'selectedAnggota' => $selectedAnggota,
            'saya' => $saya,
            'targetAnggota' => $targetAnggota
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

    public function destroy(RiwayatVaksin $riwayat): RedirectResponse
    {
        if ($riwayat->anggotaKeluarga->user_id !== Auth::id()) {
            abort(403);
        }

        if ($riwayat->status === 'Dijadwalkan') {
            return back()->with('error', 'Status Dijadwalkan tidak dapat dihapus.');
        }

        $riwayat->delete();

        return back()->with('success', 'Riwayat vaksin dihapus.');
    }
}
