<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnggotaKeluarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'nama', 'nik', 'hubungan', 'hubungan_lainnya', 'tanggal_lahir', 'jenis_kelamin'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPeranLabelAttribute()
    {
        if ($this->hubungan === 'lainnya' && $this->hubungan_lainnya) {
            return ucwords($this->hubungan_lainnya);
        }
        return str_replace('_', ' ', ucwords($this->hubungan, '_'));
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
