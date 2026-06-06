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
        $anggotaKeluarga = Auth::user()->anggotaKeluargas()->get();
        return view('keluarga.index', compact('anggotaKeluarga'));
    }

    public function store(StoreKeluargaRequest $request): RedirectResponse
    {
        Auth::user()->anggotaKeluargas()->create($request->validated());
        return redirect()->route('keluarga.index')->with('success', 'Anggota keluarga berhasil ditambahkan.');
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
