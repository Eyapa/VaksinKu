<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalVaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'anggota_keluarga_id', 'vaksin_id', 'faskes_id', 'tanggal_jadwal', 'jam_mulai',
        'jam_selesai', 'nomor_antrian', 'status', 'reminder_sent_at',
        'nomor_dosis', 'nomor_batch', 'nama_tenaga_medis', 'nomor_sertifikat', 'catatan'
    ];

    protected $casts = [
        'tanggal_jadwal' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class);
    }

    public function faskes()
    {
        return $this->belongsTo(FasilitasKesehatan::class, 'faskes_id');
    }
}
