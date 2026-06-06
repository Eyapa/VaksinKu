<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreKeluargaRequest;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KeluargaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $query = $user->anggotaKeluargas()->with(['riwayatVaksin.vaksin', 'jadwalVaksin']);
        
        if ($user->nik) {
            $query->where('nik', '!=', $user->nik);
        }
        
        $keluarga = $query->get();
        
        $stats = [
            'total' => $keluarga->count(),
            'selesai' => 0,
            'proses' => 0,
            'dijadwalkan' => 0,
            'tidak_ada' => 0
        ];

        foreach ($keluarga as $anggota) {
            $latestRiwayat = $anggota->riwayatVaksin->where('status', 'Selesai')->sortByDesc('tanggal_vaksin')->first();
            $latestJadwal = $anggota->jadwalVaksin->where('tanggal_jadwal', '>=', now()->toDateString())->sortBy('tanggal_jadwal')->first();
            
            $anggota->vaksin_terakhir = $latestRiwayat && $latestRiwayat->vaksin ? $latestRiwayat->vaksin->nama : '-';
            
            if ($latestJadwal) {
                $anggota->status_vaksin = 'Dijadwalkan';
                $anggota->status_color = 'warning-orange';
                $stats['dijadwalkan']++;
            } elseif ($latestRiwayat) {
                if (\Carbon\Carbon::parse($latestRiwayat->tanggal_vaksin)->diffInDays(now()) > 1) {
                    $anggota->status_vaksin = 'Tidak Ada Jadwal';
                    $anggota->status_color = 'outline';
                    $stats['tidak_ada']++;
                } else {
                    $anggota->status_vaksin = 'Selesai';
                    $anggota->status_color = 'success-green';
                    $stats['selesai']++;
                }
            } else {
                $anggota->status_vaksin = 'Tidak Ada Jadwal';
                $anggota->status_color = 'outline';
                $stats['tidak_ada']++;
            }
        }

        return view('keluarga.index', compact('keluarga', 'stats'));
    }

    public function create(): View
    {
        return view('keluarga.create');
    }

    public function store(StoreKeluargaRequest $request): RedirectResponse
    {
        Auth::user()->anggotaKeluargas()->create($request->validated());
        return redirect()->route('keluarga.index')->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function edit(AnggotaKeluarga $keluarga): View
    {
        if ($keluarga->user_id !== Auth::id()) {
            abort(403);
        }
        return view('keluarga.edit', compact('keluarga'));
    }

    public function update(StoreKeluargaRequest $request, AnggotaKeluarga $keluarga): RedirectResponse
    {
        if ($keluarga->user_id !== Auth::id()) {
            abort(403);
        }
        $keluarga->update($request->validated());
        return redirect()->route('keluarga.index')->with('success', 'Data anggota keluarga berhasil diperbarui.');
    }

    public function destroy(AnggotaKeluarga $keluarga): RedirectResponse
    {
        if ($keluarga->user_id !== Auth::id()) {
            abort(403);
        }
        $keluarga->delete();
        return back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
