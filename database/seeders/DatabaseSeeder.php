<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VilleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ArticleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            VilleSeeder::class,
            UserSeeder::class,
            ArticleSeeder::class,
        ]);

        // Créer des données de test avec les factories
        if (app()->environment('local')) {
            // Créer des clients de test
            \App\Models\Client::factory(20)->create();

            // Créer des approvisionnements avec des lots
            \App\Models\Approvisionnement::factory(10)->create()->each(function ($approvisionnement) {
                // Créer 2-5 lots par approvisionnement
                \App\Models\Lot::factory(rand(2, 5))->create([
                    'approvisionnement_id' => $approvisionnement->id,
                    'article_id' => \App\Models\Article::inRandomOrder()->first()->id,
                    'ville_id' => \App\Models\Ville::inRandomOrder()->first()->id,
                ]);
            });

            // Créer des ventes avec des lignes de vente
            \App\Models\Vente::factory(30)->create()->each(function ($vente) {
                // Créer 1-4 lignes par vente
                \App\Models\LigneVente::factory(rand(1, 4))->create([
                    'vente_id' => $vente->id,
                    'article_id' => \App\Models\Article::inRandomOrder()->first()->id,
                    'lot_id' => \App\Models\Lot::where('quantite_restante', '>', 0)->inRandomOrder()->first()->id,
                ]);
            });
        }
    }
}
