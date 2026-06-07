<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasKesehatan;
use App\Models\Vaksin;
use Illuminate\Support\Facades\DB;

class FasilitasVaksinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faskesList = FasilitasKesehatan::all();
        $vaksinList = Vaksin::all();
        $statuses = ['Tersedia', 'Hampir Penuh', 'Habis'];

        if ($faskesList->isEmpty() || $vaksinList->isEmpty()) {
            return;
        }

        $pivotData = [];

        foreach ($faskesList as $faskes) {
            // Assign 3 to 8 random vaccines per faskes
            $numVaksin = rand(3, 8);
            $randomVaksins = $vaksinList->random(min($numVaksin, $vaksinList->count()));

            foreach ($randomVaksins as $vaksin) {
                // Bias slightly towards "Tersedia"
                $statusRoll = rand(1, 10);
                if ($statusRoll <= 6) {
                    $status = 'Tersedia';
                } elseif ($statusRoll <= 8) {
                    $status = 'Hampir Penuh';
                } else {
                    $status = 'Habis';
                }

                $pivotData[] = [
                    'faskes_id' => $faskes->id,
                    'vaksin_id' => $vaksin->id,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to avoid query length limits
        $chunks = array_chunk($pivotData, 200);
        foreach ($chunks as $chunk) {
            DB::table('fasilitas_vaksins')->insert($chunk);
        }
    }
}
