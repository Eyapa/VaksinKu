<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama', 'kode', 'jenis', 'dosis_total', 'interval_hari', 'usia_minimal_bulan', 'usia_maksimal_tahun', 'deskripsi', 'produsen'
    ];

    public function riwayatVaksin()
    {
        return $this->hasMany(RiwayatVaksin::class);
    }

    public function jadwalVaksin()
    {
        return $this->hasMany(JadwalVaksin::class);
    }
}
