<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\FasilitasKesehatan;
use App\Models\JadwalVaksin;
use App\Models\Vaksin;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DummyVaksinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaksins = Vaksin::all();
        $faskes = FasilitasKesehatan::all();

        if ($vaksins->isEmpty() || $faskes->isEmpty()) {
            $this->command->warn('Vaksin atau Faskes kosong. Pastikan DatabaseSeeder sudah dijalankan.');
            return;
        }

        // Ensure a user exists and has AnggotaKeluarga records
        $user = \App\Models\User::where('email', 'pasien@vaksinku.test')->first();
        if ($user) {
            // Update NIK if empty
            if (!$user->nik) {
                $user->update(['nik' => '350' . rand(1000000000000, 9999999999999)]);
            }

            // Create AnggotaKeluarga for the user themselves
            AnggotaKeluarga::firstOrCreate(
                ['user_id' => $user->id, 'nik' => $user->nik],
                [
                    'nama' => $user->name,
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'L',
                    'hubungan' => 'kepala_keluarga',
                ]
            );

            // Create a dummy child
            AnggotaKeluarga::firstOrCreate(
                ['user_id' => $user->id, 'hubungan' => 'anak'],
                [
                    'nama' => 'Adit Santoso',
                    'nik' => '350' . rand(1000000000000, 9999999999999),
                    'tanggal_lahir' => Carbon::now()->subYears(2),
                    'jenis_kelamin' => 'L',
                ]
            );
        }

        $anggotaKeluargas = AnggotaKeluarga::all();

        foreach ($anggotaKeluargas as $anggota) {
            // Berikan 1-3 riwayat selesai
            $jumlahRiwayat = rand(1, 3);
            for ($i = 0; $i < $jumlahRiwayat; $i++) {
                JadwalVaksin::create([
                    'anggota_keluarga_id' => $anggota->id,
                    'vaksin_id' => $vaksins->random()->id,
                    'faskes_id' => $faskes->random()->id,
                    'nomor_dosis' => $i + 1,
                    'tanggal_jadwal' => Carbon::now()->subMonths(rand(1, 24)),
                    'status' => 'selesai',
                    'nomor_batch' => 'BATCH-' . strtoupper(Str::random(5)),
                    'nama_tenaga_medis' => 'Dr. ' . fake()->lastName(),
                    'nomor_sertifikat' => 'VKS-' . strtoupper(Str::random(10)),
                    'file_sertifikat_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                ]);
            }

            // Berikan 1 riwayat memproses (opsional)
            if (rand(0, 1)) {
                JadwalVaksin::create([
                    'anggota_keluarga_id' => $anggota->id,
                    'vaksin_id' => $vaksins->random()->id,
                    'faskes_id' => $faskes->random()->id,
                    'nomor_dosis' => 1,
                    'tanggal_jadwal' => Carbon::now()->subDays(rand(1, 7)),
                    'status' => 'konfirmasi',
                    'file_sertifikat_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                ]);
            }

            // Berikan 1 jadwal ke depan (opsional)
            if (rand(0, 1)) {
                JadwalVaksin::create([
                    'anggota_keluarga_id' => $anggota->id,
                    'vaksin_id' => $vaksins->random()->id,
                    'faskes_id' => $faskes->random()->id,
                    'tanggal_jadwal' => Carbon::now()->addDays(rand(1, 30)),
                    'jam_mulai' => '09:00:00',
                    'jam_selesai' => '10:00:00',
                    'status' => 'terdaftar',
                    'file_sertifikat_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                ]);
            }
        }
    }
}
