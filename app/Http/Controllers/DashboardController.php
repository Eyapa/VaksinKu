<?php
namespace App\Http\Controllers;

use App\Services\VaksinScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(VaksinScheduleService $scheduleService): View
    {
        $user = Auth::user();
        $anggotaKeluarga = $user->anggotaKeluargas()->with('riwayatVaksin.vaksin')->get();
        $persentaseCakupan = $scheduleService->hitungCakupanKeluarga($user);
        
        return view('dashboard.index', compact('user', 'anggotaKeluarga', 'persentaseCakupan'));
    }
}
