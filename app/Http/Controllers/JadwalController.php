<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalRequest;
use App\Models\JadwalVaksin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        $anggotaIds = Auth::user()->anggotaKeluargas()->pluck('id');
        $jadwal = JadwalVaksin::with(['vaksin', 'faskes', 'anggotaKeluarga'])
            ->whereIn('anggota_keluarga_id', $anggotaIds)
            ->latest('tanggal_jadwal')
            ->paginate(15);
            
        return view('jadwal.index', compact('jadwal'));
    }

    public function store(StoreJadwalRequest $request): RedirectResponse
    {
        JadwalVaksin::create($request->validated());
        return redirect()->route('jadwal.index')->with('success', 'Jadwal vaksin berhasil dibuat.');
    }

    public function destroy(JadwalVaksin $jadwal): RedirectResponse
    {
        if ($jadwal->anggotaKeluarga->user_id !== Auth::id()) {
            abort(403);
        }
        $jadwal->delete();
        return back()->with('success', 'Jadwal vaksin berhasil dibatalkan.');
    }
}
