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

        $anggotaIds = $user->anggotaKeluargas()->pluck('id');

        $riwayats = RiwayatVaksin::with(['vaksin', 'faskes', 'anggotaKeluarga'])
            ->whereIn('anggota_keluarga_id', $anggotaIds)
            ->latest('tanggal_vaksin')
            ->paginate(15);

        $persentaseCakupan = $this->scheduleService
            ->hitungCakupanKeluarga($user);

        return view('riwayat.index', compact('riwayats', 'persentaseCakupan'));
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

        $riwayat->delete();

        return back()->with('success', 'Riwayat vaksin dihapus.');
    }
}
