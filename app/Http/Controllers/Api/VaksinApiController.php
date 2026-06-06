<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Services\VaksinScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VaksinApiController extends Controller
{
    public function jadwal($anggotaId, VaksinScheduleService $scheduleService): JsonResponse
    {
        $anggota = AnggotaKeluarga::findOrFail($anggotaId);
        
        // Ensure the member belongs to the auth user
        if ($anggota->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized', 'code' => 403], 403);
        }

        $target = $scheduleService->getVaksinTargetUntukUsia($anggota);

        return response()->json([
            'success' => true,
            'data' => $target,
            'message' => 'OK'
        ]);
    }
}
