<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasKesehatan;

class FaskesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['kota' => 'Jakarta Selatan', 'provinsi' => 'DKI Jakarta', 'lat' => -6.2088, 'lng' => 106.8456],
            ['kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'lat' => -7.2504, 'lng' => 112.7688],
            ['kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'lat' => -6.9147, 'lng' => 107.6098],
            ['kota' => 'Medan', 'provinsi' => 'Sumatera Utara', 'lat' => 3.5952, 'lng' => 98.6722],
            ['kota' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'lat' => -6.9667, 'lng' => 110.4167],
            ['kota' => 'Makassar', 'provinsi' => 'Sulawesi Selatan', 'lat' => -5.1476, 'lng' => 119.4327],
            ['kota' => 'Palembang', 'provinsi' => 'Sumatera Selatan', 'lat' => -2.9909, 'lng' => 104.7566],
            ['kota' => 'Tangerang', 'provinsi' => 'Banten', 'lat' => -6.1702, 'lng' => 106.6403],
            ['kota' => 'Depok', 'provinsi' => 'Jawa Barat', 'lat' => -6.4025, 'lng' => 106.7942],
            ['kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'lat' => -6.2383, 'lng' => 106.9756],
            ['kota' => 'Bogor', 'provinsi' => 'Jawa Barat', 'lat' => -6.5971, 'lng' => 106.7905],
            ['kota' => 'Batam', 'provinsi' => 'Kepulauan Riau', 'lat' => 1.0828, 'lng' => 104.0305],
            ['kota' => 'Pekanbaru', 'provinsi' => 'Riau', 'lat' => 0.5071, 'lng' => 101.4478],
            ['kota' => 'Bandar Lampung', 'provinsi' => 'Lampung', 'lat' => -5.4500, 'lng' => 105.2667],
            ['kota' => 'Padang', 'provinsi' => 'Sumatera Barat', 'lat' => -0.9471, 'lng' => 100.3658],
            ['kota' => 'Malang', 'provinsi' => 'Jawa Timur', 'lat' => -7.9839, 'lng' => 112.6214],
            ['kota' => 'Denpasar', 'provinsi' => 'Bali', 'lat' => -8.6500, 'lng' => 115.2167],
            ['kota' => 'Samarinda', 'provinsi' => 'Kalimantan Timur', 'lat' => -0.5022, 'lng' => 117.1536],
            ['kota' => 'Balikpapan', 'provinsi' => 'Kalimantan Timur', 'lat' => -1.2379, 'lng' => 116.8529],
            ['kota' => 'Banjarmasin', 'provinsi' => 'Kalimantan Selatan', 'lat' => -3.3194, 'lng' => 114.5908],
            ['kota' => 'Serang', 'provinsi' => 'Banten', 'lat' => -6.1200, 'lng' => 106.1503],
            ['kota' => 'Pontianak', 'provinsi' => 'Kalimantan Barat', 'lat' => -0.0227, 'lng' => 109.3333],
            ['kota' => 'Jambi', 'provinsi' => 'Jambi', 'lat' => -1.6101, 'lng' => 103.6131],
            ['kota' => 'Surakarta', 'provinsi' => 'Jawa Tengah', 'lat' => -7.5666, 'lng' => 110.8166],
            ['kota' => 'Manado', 'provinsi' => 'Sulawesi Utara', 'lat' => 1.4931, 'lng' => 124.8413],
            ['kota' => 'Mataram', 'provinsi' => 'Nusa Tenggara Barat', 'lat' => -8.5833, 'lng' => 116.1167],
            ['kota' => 'Yogyakarta', 'provinsi' => 'DI Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695],
            ['kota' => 'Ambon', 'provinsi' => 'Maluku', 'lat' => -3.6954, 'lng' => 128.1814],
            ['kota' => 'Kupang', 'provinsi' => 'Nusa Tenggara Timur', 'lat' => -10.1583, 'lng' => 123.5833],
            ['kota' => 'Palu', 'provinsi' => 'Sulawesi Tengah', 'lat' => -0.8917, 'lng' => 119.8707],
            ['kota' => 'Bengkulu', 'provinsi' => 'Bengkulu', 'lat' => -3.7928, 'lng' => 102.2601],
            ['kota' => 'Jayapura', 'provinsi' => 'Papua', 'lat' => -2.5337, 'lng' => 140.7181],
        ];

        foreach ($cities as $city) {
            // Generate 2 facilities for each city
            for ($i = 1; $i <= 2; $i++) {
                $isPuskesmas = $i == 1;
                $faskesType = $isPuskesmas ? 'puskesmas' : 'rs_umum';
                $faskesPrefix = $isPuskesmas ? 'Puskesmas' : 'RSUD';
                
                // Slight randomization for coordinates
                $latOffset = (rand(-100, 100) / 10000);
                $lngOffset = (rand(-100, 100) / 10000);

                FasilitasKesehatan::updateOrCreate(
                    ['nama' => $faskesPrefix . ' ' . $city['kota'] . ' ' . $i],
                    [
                        'jenis' => $faskesType,
                        'kota' => $city['kota'],
                        'provinsi' => $city['provinsi'],
                        'latitude' => $city['lat'] + $latOffset,
                        'longitude' => $city['lng'] + $lngOffset,
                        'telepon' => '021-' . rand(1000000, 9999999),
                        'layanan_vaksin' => true,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
