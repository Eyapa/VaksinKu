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
        
        $semuaAnggota = $user->anggotaKeluargas()->with(['jadwalVaksin.vaksin'])->get();
        
        $mainProfile = $semuaAnggota->where('nik', $user->nik)->first();
        $keluargaLain = $semuaAnggota->where('nik', '!=', $user->nik)->map(function ($anggota) {
            $status = 'Belum Divaksin';
            $statusType = 'secondary';
            $statusIcon = 'help';
            
            $jadwalTerbaru = $anggota->jadwalVaksin->where('tanggal_jadwal', '>=', now()->toDateString())->whereIn('status', ['terdaftar', 'konfirmasi'])->sortBy('tanggal_jadwal')->first();
            $riwayatSelesai = $anggota->jadwalVaksin->where('status', 'selesai')->sortByDesc('tanggal_jadwal')->first();
            
            if ($jadwalTerbaru) {
                $status = 'Menunggu ' . $jadwalTerbaru->vaksin->nama;
                $statusType = 'warning-orange';
                $statusIcon = 'schedule';
            } elseif ($riwayatSelesai) {
                $status = 'Selesai ' . $riwayatSelesai->vaksin->nama;
                $statusType = 'success-green';
                $statusIcon = 'check_circle';
            }
            
            $anggota->status_terakhir_text = $status;
            $anggota->status_terakhir_type = $statusType;
            $anggota->status_terakhir_icon = $statusIcon;
            
            return $anggota;
        });
        
        $persentaseCakupan = $scheduleService->hitungCakupanKeluarga($user);
        
        $pengumuman = \App\Models\Pengumuman::where('is_aktif', true)
                        ->where(function($q) {
                            $q->whereNull('tanggal_berlaku')
                              ->orWhere('tanggal_berlaku', '>=', now());
                        })
                        ->latest()
                        ->first();
        
        return view('dashboard.index', compact('user', 'mainProfile', 'keluargaLain', 'persentaseCakupan', 'pengumuman'));
    }
}
