<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul', 'konten', 'tanggal_berlaku', 'is_aktif'
    ];
}
