<?php
// app/Console/Commands/SyncFaskesFromSatuSehat.php

namespace App\Console\Commands;

use App\Models\FasilitasKesehatan;
use App\Services\SatuSehatService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncFaskesFromSatuSehat extends Command
{
    protected $signature = 'vaksinku:sync-faskes
                            {--nama= : Filter berdasarkan nama faskes}
                            {--kota= : Filter berdasarkan nama kota}
                            {--limit=10 : Maksimal jumlah faskes}';
    protected $description = 'Sinkronisasi data faskes dari SATUSEHAT API ke database lokal, dengan fallback mock data jika gagal.';

    public function handle(SatuSehatService $satuSehat): int
    {
        $this->info('🔄 Memulai sinkronisasi faskes dari SATUSEHAT...');

        $nama = $this->option('nama');
        $kota = $this->option('kota');
        $limit = (int) $this->option('limit');

        $this->info("Memulai sinkronisasi faskes dari SATUSEHAT...");
        if ($nama) $this->info("Filter Nama: $nama");
        if ($kota) $this->info("Filter Kota: $kota");

        try {
            $searchParts = [];
            if ($nama) $searchParts[] = $nama;
            if ($kota) $searchParts[] = $kota;
            
            $searchKeyword = implode(' ', $searchParts);

            // Ambil organisasi dari SATUSEHAT
            $organizations = $searchKeyword
                ? $satuSehat->searchOrganization($searchKeyword, $limit)
                : $satuSehat->getOrganizationByPartOf('pemerintah', $limit);

            $bar = $this->output->createProgressBar(count($organizations));
            $bar->start();

            foreach ($organizations as $org) {
                $normalized = $satuSehat->normalizeOrganization($org);

                if (empty($normalized['satusehat_id'])) {
                    continue;
                }

                FasilitasKesehatan::updateOrCreate(
                    ['satusehat_id' => $normalized['satusehat_id']],
                    $normalized
                );

                $synced++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("✅ Selesai: {$synced} faskes berhasil disinkronisasi dari SATUSEHAT API.");

        } catch (\Exception $e) {
            $this->error('❌ Gagal menghubungi API SATUSEHAT atau autentikasi ditolak.');
            $this->error('Pesan: ' . $e->getMessage());
            $this->warn('⚠️ Menggunakan MOCK DATA sebagai fallback untuk faskes...');
            Log::warning('Sinkronisasi SATUSEHAT gagal, menggunakan fallback mock data.', ['error' => $e->getMessage()]);

            $this->seedMockData();
        }

        return Command::SUCCESS;
    }

    private function seedMockData()
    {
        $mockData = [
            [
                'satusehat_id' => 'mock-1001',
                'nama' => 'Puskesmas Kecamatan Tebet',
                'jenis' => 'puskesmas',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'alamat' => 'Jl. Prof. DR. Soepomo No.54, RT.13/RW.3, Tebet Bar., Kec. Tebet',
                'latitude' => -6.2300,
                'longitude' => 106.8450,
                'telepon' => '021-8291234',
                'layanan_vaksin' => true,
                'is_active' => true,
            ],
            [
                'satusehat_id' => 'mock-1002',
                'nama' => 'RSUD Pasar Minggu',
                'jenis' => 'rs_umum',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'alamat' => 'Jl. TB Simatupang No.1, RT.1/RW.5, Ragunan, Kec. Ps. Minggu',
                'latitude' => -6.2916,
                'longitude' => 106.8206,
                'telepon' => '021-29059999',
                'layanan_vaksin' => true,
                'is_active' => true,
            ],
            [
                'satusehat_id' => 'mock-1003',
                'nama' => 'Klinik Pratama Sehat Bersama',
                'jenis' => 'klinik',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'alamat' => 'Jl. R.E. Martadinata No.50, Citarum, Kec. Bandung Wetan',
                'latitude' => -6.9065,
                'longitude' => 107.6186,
                'telepon' => '022-4231122',
                'layanan_vaksin' => true,
                'is_active' => true,
            ],
            [
                'satusehat_id' => 'mock-1004',
                'nama' => 'RS Dr. Hasan Sadikin',
                'jenis' => 'rs_umum',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'alamat' => 'Jl. Pasteur No.38, Pasteur, Kec. Sukajadi',
                'latitude' => -6.8967,
                'longitude' => 107.5981,
                'telepon' => '022-2034953',
                'layanan_vaksin' => true,
                'is_active' => true,
            ],
        ];

        foreach ($mockData as $data) {
            FasilitasKesehatan::updateOrCreate(
                ['satusehat_id' => $data['satusehat_id']],
                $data
            );
        }

        $this->info("✅ Fallback selesai: " . count($mockData) . " mock faskes berhasil ditambahkan.");
    }
}
