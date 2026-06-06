<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatVaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'anggota_keluarga_id', 'vaksin_id', 'faskes_id', 'nomor_dosis', 'tanggal_vaksin',
        'nomor_batch', 'nama_tenaga_medis', 'nomor_sertifikat', 'status', 'catatan'
    ];

    protected $casts = [
        'tanggal_vaksin' => 'date',
        'nomor_dosis' => 'integer',
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
