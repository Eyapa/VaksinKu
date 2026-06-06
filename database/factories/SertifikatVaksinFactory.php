<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SertifikatVaksin>
 */
class SertifikatVaksinFactory extends Factory
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
            'nomor_sertifikat' => 'VK-' . date('Y') . '-' . strtoupper(fake()->bothify('????????')),
            'qr_code_data' => fake()->url(),
            'tanggal_terbit' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'pdf_path' => 'sertifikat/' . fake()->uuid() . '.pdf',
        ];
    }
}
