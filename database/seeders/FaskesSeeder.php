<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasKesehatan;

class FaskesSeeder extends Seeder
{
    public function run(): void
    {
        $faskesContoh = [
            [
                'nama' => 'Puskesmas Kecamatan Tebet',
                'jenis' => 'puskesmas',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'latitude' => -6.2088, 'longitude' => 106.8456,
                'telepon' => '021-8291234',
                'layanan_vaksin' => true,
            ],
            [
                'nama' => 'RSUD Kebayoran Baru',
                'jenis' => 'rs_umum',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'latitude' => -6.2345, 'longitude' => 106.8012,
                'telepon' => '021-7221234',
                'layanan_vaksin' => true,
            ],
        ];

        foreach ($faskesContoh as $data) {
            FasilitasKesehatan::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
