<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FasilitasKesehatan>
 */
class FasilitasKesehatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'osm_id' => fake()->unique()->numerify('#########'),
            'satusehat_id' => fake()->unique()->uuid(),
            'nama' => fake('id_ID')->company() . ' Health Clinic',
            'jenis' => fake()->randomElement(['puskesmas', 'posyandu', 'klinik', 'rs']),
            'alamat' => fake('id_ID')->address(),
            'kelurahan' => fake('id_ID')->streetName(),
            'kecamatan' => fake('id_ID')->citySuffix(),
            'kota' => fake('id_ID')->city(),
            'provinsi' => fake('id_ID')->state(),
            'latitude' => fake()->latitude(-8.5, -6.0),
            'longitude' => fake()->longitude(106.0, 114.0),
            'telepon' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'email' => fake()->safeEmail(),
            'jam_buka' => '08:00',
            'jam_tutup' => '15:00',
            'hari_operasional' => 'Senin-Jumat',
            'layanan_vaksin' => true,
            'rating' => fake()->randomFloat(1, 3, 5),
            'foto_url' => null,
            'is_active' => true,
        ];
    }
}
