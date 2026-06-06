<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vaksin>
 */
class VaksinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['BCG', 'Hepatitis B', 'Polio Oral', 'DPT-HB-HiB', 'Measles-Rubella']),
            'kode' => fake()->unique()->lexify('???'),
            'jenis' => fake()->randomElement(['wajib', 'pilihan']),
            'dosis_total' => fake()->numberBetween(1, 4),
            'interval_hari' => fake()->randomElement([0, 30, 60, 180]),
            'usia_minimal_bulan' => fake()->numberBetween(0, 12),
            'usia_maksimal_tahun' => fake()->randomElement([1, 5, 12, null]),
            'deskripsi' => fake()->sentence(),
            'produsen' => fake()->company(),
        ];
    }
}
