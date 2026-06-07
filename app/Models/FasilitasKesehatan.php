<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FasilitasKesehatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'osm_id', 'satusehat_id', 'nama', 'jenis', 'alamat', 'kelurahan', 'kecamatan', 'kota', 'provinsi',
        'latitude', 'longitude', 'telepon', 'website', 'email', 'jam_buka', 'jam_tutup', 'hari_operasional',
        'layanan_vaksin', 'rating', 'foto_url', 'is_active'
    ];

    protected $casts = [
        'layanan_vaksin' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function jadwalVaksin()
    {
        return $this->hasMany(JadwalVaksin::class, 'faskes_id');
    }

    public function vaksins()
    {
        return $this->belongsToMany(Vaksin::class, 'fasilitas_vaksins', 'faskes_id', 'vaksin_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
