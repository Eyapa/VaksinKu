<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'kode',
        'jenis',
        'dosis_total',
        'interval_hari',
        'usia_minimal_bulan',
        'usia_maksimal_tahun',
        'produsen',
    ];

    public function jadwalVaksin()
    {
        return $this->hasMany(JadwalVaksin::class);
    }

    public function fasilitasKesehatans()
    {
        return $this->belongsToMany(FasilitasKesehatan::class, 'fasilitas_vaksins', 'vaksin_id', 'faskes_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
