<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FasilitasKesehatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaskesApiController extends Controller
{
    public function nearby(Request $request): JsonResponse
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $radius = $request->query('radius', 5); // km

        // Simplified: return seeded faskes
        // A real implementation would use Haversine formula to filter by radius
        $faskes = FasilitasKesehatan::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $faskes,
            'message' => 'OK'
        ]);
    }

    public function show($id): JsonResponse
    {
        $faskes = FasilitasKesehatan::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $faskes,
            'message' => 'OK'
        ]);
    }
}
