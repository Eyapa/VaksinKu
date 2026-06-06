<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatVaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'anggota_keluarga_id', 'vaksin_id', 'nomor_sertifikat', 'qr_code_data', 'tanggal_terbit', 'pdf_path'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class);
    }
}
