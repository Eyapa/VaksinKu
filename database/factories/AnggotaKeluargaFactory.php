<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnggotaKeluarga>
 */
class AnggotaKeluargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'nama' => fake('id_ID')->name(),
            'nik' => fake()->unique()->numerify('################'),
            'hubungan' => fake()->randomElement(['kepala_keluarga', 'istri', 'anak', 'orang_tua']),
            'tanggal_lahir' => fake()->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'no_kartu_vaksin' => fake()->optional()->numerify('VK-##########'),
        ];
    }
}
