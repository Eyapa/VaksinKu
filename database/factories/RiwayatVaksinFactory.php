<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatVaksin>
 */
class RiwayatVaksinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'anggota_keluarga_id' => \App\Models\AnggotaKeluarga::factory(),
            'vaksin_id' => \App\Models\Vaksin::factory(),
            'faskes_id' => \App\Models\FasilitasKesehatan::factory(),
            'nomor_dosis' => fake()->numberBetween(1, 3),
            'tanggal_vaksin' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'nomor_batch' => strtoupper(fake()->bothify('??###??')),
            'nama_tenaga_medis' => 'dr. ' . fake('id_ID')->name(),
            'nomor_sertifikat' => 'VK-' . date('Y') . '-' . strtoupper(fake()->bothify('????????')),
            'status' => fake()->randomElement(['selesai', 'pending', 'batal']),
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
