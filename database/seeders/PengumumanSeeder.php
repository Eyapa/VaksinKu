<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Pengumuman::create([
            'judul' => 'Vaksinasi Keliling hadir di Balai Desa Sukamaju',
            'konten' => 'Hari Sabtu, 24 Mei 2024. Melayani dosis 1, 2, dan Booster untuk seluruh warga desa. Jangan lupa bawa KTP!',
            'tanggal_berlaku' => \Carbon\Carbon::now()->addDays(1),
            'is_aktif' => true,
        ]);
    }
}
