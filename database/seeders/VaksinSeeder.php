<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaksin;

class VaksinSeeder extends Seeder
{
    public function run(): void
    {
        $vaksinData = [
            ['nama' => 'COVID-19 (Primer)', 'kode' => 'COVID-P', 'jenis' => 'covid', 'dosis_total' => 2, 'interval_hari' => 28, 'usia_minimal_bulan' => 216, 'produsen' => 'Sinovac/Pfizer/AstraZeneca'],
            ['nama' => 'COVID-19 (Booster 1)', 'kode' => 'COVID-B1', 'jenis' => 'covid', 'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216, 'produsen' => null],
            ['nama' => 'COVID-19 (Booster 2)', 'kode' => 'COVID-B2', 'jenis' => 'covid', 'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216, 'produsen' => null],
            ['nama' => 'Influenza (Tahunan)', 'kode' => 'FLU', 'jenis' => 'influenza', 'dosis_total' => 1, 'interval_hari' => 365, 'usia_minimal_bulan' => 6, 'produsen' => null],
            ['nama' => 'Hepatitis B (Neonatus)', 'kode' => 'HBV-0', 'jenis' => 'hepatitis', 'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0, 'produsen' => null],
            ['nama' => 'BCG (Tuberkulosis)', 'kode' => 'BCG', 'jenis' => 'bcg', 'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0, 'usia_maksimal_tahun' => 0, 'produsen' => null],
            ['nama' => 'Polio Oral (OPV)', 'kode' => 'OPV', 'jenis' => 'polio', 'dosis_total' => 4, 'interval_hari' => 56, 'usia_minimal_bulan' => 0, 'produsen' => null],
            ['nama' => 'Polio Inaktif (IPV)', 'kode' => 'IPV', 'jenis' => 'polio', 'dosis_total' => 2, 'interval_hari' => 56, 'usia_minimal_bulan' => 0, 'produsen' => null],
            ['nama' => 'DPT-HB-HiB (Pentavalen)', 'kode' => 'DPT', 'jenis' => 'difteri', 'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'produsen' => null],
            ['nama' => 'DT (Difteri Tetanus) Booster', 'kode' => 'DT', 'jenis' => 'difteri', 'dosis_total' => 2, 'interval_hari' => 365, 'usia_minimal_bulan' => 0, 'produsen' => null],
            ['nama' => 'MR (Measles-Rubella)', 'kode' => 'MR', 'jenis' => 'mmr', 'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 9, 'produsen' => null],
            ['nama' => 'PCV (Pneumococcal)', 'kode' => 'PCV', 'jenis' => 'pneumococcal', 'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'produsen' => null],
            ['nama' => 'Rotavirus', 'kode' => 'RV', 'jenis' => 'rotavirus', 'dosis_total' => 2, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'usia_maksimal_tahun' => 1, 'produsen' => null],
            ['nama' => 'HPV (Human Papillomavirus)', 'kode' => 'HPV', 'jenis' => 'hpv', 'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 120, 'usia_maksimal_tahun' => 13, 'produsen' => null],
            ['nama' => 'Typhoid (Tifus)', 'kode' => 'TYP', 'jenis' => 'typhoid', 'dosis_total' => 1, 'interval_hari' => 1095, 'usia_minimal_bulan' => 24, 'produsen' => null],
        ];

        foreach ($vaksinData as $data) {
            Vaksin::updateOrCreate(['kode' => $data['kode']], $data);
        }
    }
}
