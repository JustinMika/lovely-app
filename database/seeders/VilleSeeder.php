<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ville;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villes = [
            ['nom' => 'Kinshasa'],
            ['nom' => 'Goma'],
            ['nom' => 'Lubumbashi'],
            ['nom' => 'Bukavu'],
            ['nom' => 'Matadi'],
            ['nom' => 'Kisangani'],
            ['nom' => 'Kananga'],
            ['nom' => 'Mbuji-Mayi'],
        ];

        foreach ($villes as $ville) {
            Ville::firstOrCreate($ville);
        }
    }
}
