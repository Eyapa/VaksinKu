<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnggotaKeluarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'nama', 'nik', 'hubungan', 'tanggal_lahir', 'jenis_kelamin', 'no_kartu_vaksin'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riwayatVaksin()
    {
        return $this->hasMany(RiwayatVaksin::class);
    }

    public function jadwalVaksin()
    {
        return $this->hasMany(JadwalVaksin::class);
    }
    
    public function sertifikatVaksin()
    {
        return $this->hasMany(SertifikatVaksin::class);
    }
}
