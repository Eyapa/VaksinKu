<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaksin;

class VaksinSeeder extends Seeder
{
    public function run(): void
    {
        $vaksinData = [
            ['nama' => 'COVID-19 (Primer)', 'kode' => 'COVID-P', 'jenis' => 'covid', 'dosis_total' => 2, 'interval_hari' => 28, 'usia_minimal_bulan' => 216, 'produsen' => 'Sinovac/Pfizer/AstraZeneca', 'deskripsi' => 'Vaksinasi dosis primer untuk mencegah infeksi virus SARS-CoV-2 (COVID-19). Diberikan dua dosis.'],
            ['nama' => 'COVID-19 (Booster 1)', 'kode' => 'COVID-B1', 'jenis' => 'covid', 'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216, 'produsen' => null, 'deskripsi' => 'Vaksinasi penguat pertama (booster) untuk meningkatkan antibodi terhadap COVID-19.'],
            ['nama' => 'COVID-19 (Booster 2)', 'kode' => 'COVID-B2', 'jenis' => 'covid', 'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216, 'produsen' => null, 'deskripsi' => 'Vaksinasi penguat kedua (booster) yang umumnya diperuntukkan bagi lansia dan kelompok rentan.'],
            ['nama' => 'Influenza (Tahunan)', 'kode' => 'FLU', 'jenis' => 'influenza', 'dosis_total' => 1, 'interval_hari' => 365, 'usia_minimal_bulan' => 6, 'produsen' => null, 'deskripsi' => 'Vaksin tahunan untuk mencegah virus flu musiman. Sangat dianjurkan untuk segala usia.'],
            ['nama' => 'Hepatitis B (Neonatus)', 'kode' => 'HBV-0', 'jenis' => 'hepatitis', 'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0, 'produsen' => null, 'deskripsi' => 'Vaksin untuk mencegah infeksi hati kronis (Hepatitis B). Wajib diberikan pada bayi baru lahir (kurang dari 24 jam).'],
            ['nama' => 'BCG (Tuberkulosis)', 'kode' => 'BCG', 'jenis' => 'bcg', 'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0, 'usia_maksimal_tahun' => 0, 'produsen' => null, 'deskripsi' => 'Vaksin untuk mencegah penyakit Tuberkulosis (TBC) yang berat. Diberikan segera setelah lahir.'],
            ['nama' => 'Polio Oral (OPV)', 'kode' => 'OPV', 'jenis' => 'polio', 'dosis_total' => 4, 'interval_hari' => 56, 'usia_minimal_bulan' => 0, 'produsen' => null, 'deskripsi' => 'Vaksin tetes mulut untuk mencegah penyakit polio yang dapat menyebabkan kelumpuhan.'],
            ['nama' => 'Polio Inaktif (IPV)', 'kode' => 'IPV', 'jenis' => 'polio', 'dosis_total' => 2, 'interval_hari' => 56, 'usia_minimal_bulan' => 0, 'produsen' => null, 'deskripsi' => 'Vaksin suntik polio untuk memberikan kekebalan tambahan bersama dengan OPV.'],
            ['nama' => 'DPT-HB-HiB (Pentavalen)', 'kode' => 'DPT', 'jenis' => 'difteri', 'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'produsen' => null, 'deskripsi' => 'Vaksin kombinasi (pentavalen) untuk mencegah Difteri, Pertusis (batuk rejan), Tetanus, Hepatitis B, dan infeksi HiB (meningitis).'],
            ['nama' => 'DT (Difteri Tetanus) Booster', 'kode' => 'DT', 'jenis' => 'difteri', 'dosis_total' => 2, 'interval_hari' => 365, 'usia_minimal_bulan' => 0, 'produsen' => null, 'deskripsi' => 'Vaksin lanjutan untuk memperkuat kekebalan terhadap Difteri dan Tetanus pada anak usia sekolah.'],
            ['nama' => 'MR (Measles-Rubella)', 'kode' => 'MR', 'jenis' => 'mmr', 'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 9, 'produsen' => null, 'deskripsi' => 'Vaksin pencegahan penyakit Campak (Measles) dan Rubella yang sangat menular.'],
            ['nama' => 'PCV (Pneumococcal)', 'kode' => 'PCV', 'jenis' => 'pneumococcal', 'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'produsen' => null, 'deskripsi' => 'Vaksin pencegahan infeksi bakteri pneumokokus yang dapat memicu radang paru (Pneumonia) dan meningitis.'],
            ['nama' => 'Rotavirus', 'kode' => 'RV', 'jenis' => 'rotavirus', 'dosis_total' => 2, 'interval_hari' => 56, 'usia_minimal_bulan' => 2, 'usia_maksimal_tahun' => 1, 'produsen' => null, 'deskripsi' => 'Vaksin tetes untuk mencegah diare berat pada bayi dan balita yang disebabkan oleh Rotavirus.'],
            ['nama' => 'HPV (Human Papillomavirus)', 'kode' => 'HPV', 'jenis' => 'hpv', 'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 120, 'usia_maksimal_tahun' => 13, 'produsen' => null, 'deskripsi' => 'Vaksin pencegahan kanker serviks yang disarankan bagi anak perempuan usia 10-13 tahun.'],
            ['nama' => 'Typhoid (Tifus)', 'kode' => 'TYP', 'jenis' => 'typhoid', 'dosis_total' => 1, 'interval_hari' => 1095, 'usia_minimal_bulan' => 24, 'produsen' => null, 'deskripsi' => 'Vaksin pencegahan demam Tifoid (Tifus). Disarankan diulang setiap 3 tahun sekali.'],
        ];

        foreach ($vaksinData as $data) {
            Vaksin::updateOrCreate(['kode' => $data['kode']], $data);
        }
    }
}
