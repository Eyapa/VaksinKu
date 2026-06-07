<?php
namespace App\Http\Controllers;

use Illuminate\View\View;

class FaskesController extends Controller
{
    public function index(): View
    {
        $vaksins = \App\Models\Vaksin::orderBy('nama')->get();
        $anggotaKeluargas = auth()->check() ? auth()->user()->anggotaKeluargas : collect();
        return view('cari.index', compact('vaksins', 'anggotaKeluargas'));
    }

    public function apiIndex(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        // Cast coordinates and radius to float to avoid SQLite text-to-number comparison bugs
        $lat = $request->lat ? (float) $request->lat : null;
        $lng = $request->lng ? (float) $request->lng : null;
        $radius = $request->radius ? (float) $request->radius : null;
        $vaksinId = $request->input('vaksin_id');

        $query = \App\Models\FasilitasKesehatan::with('vaksins')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('is_active', true);

        if ($vaksinId) {
            $query->whereHas('vaksins', function($q) use ($vaksinId) {
                $q->where('vaksins.id', $vaksinId);
            });
        }

        if ($lat && $lng) {
            // Haversine formula
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";
            
            $query->selectRaw("fasilitas_kesehatans.*, {$haversine} AS distance", [$lat, $lng, $lat]);

            if ($radius) {
                // SQLite doesn't support HAVING without GROUP BY, so we use whereRaw
                // We MUST wrap the formula in CAST(... AS REAL) because SQLite might treat the result or the parameter as text during comparison
                $query->whereRaw("CAST({$haversine} AS REAL) <= ?", [$lat, $lng, $lat, $radius]);
            }

            $query->orderBy('distance');
        } else {
            $query->orderBy('nama');
        }

        $faskes = $query->get();
                    
        return response()->json([
            'success' => true,
            'data' => $faskes,
            'message' => 'Berhasil mengambil data faskes'
        ]);
    }
}
