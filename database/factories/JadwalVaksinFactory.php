<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalVaksin>
 */
class JadwalVaksinFactory extends Factory
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
            'tanggal_jadwal' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'nomor_antrian' => fake()->numerify('A-###'),
            'status' => fake()->randomElement(['terdaftar', 'konfirmasi', 'selesai', 'batal']),
            'reminder_sent_at' => null,
        ];
    }
}
