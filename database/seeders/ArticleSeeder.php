<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'designation' => 'Riz 25Kg',
                'description' => 'Sac de riz de 25 kilogrammes',
                'actif' => true,
            ],
            [
                'designation' => 'Riz 50Kg',
                'description' => 'Sac de riz de 50 kilogrammes',
                'actif' => true,
            ],
            [
                'designation' => 'Huile 1L',
                'description' => 'Bidon d\'huile de 1 litre',
                'actif' => true,
            ],
            [
                'designation' => 'Huile 5L',
                'description' => 'Bidon d\'huile de 5 litres',
                'actif' => true,
            ],
            [
                'designation' => 'Farine 25Kg',
                'description' => 'Sac de farine de 25 kilogrammes',
                'actif' => true,
            ],
            [
                'designation' => 'Sucre 50Kg',
                'description' => 'Sac de sucre de 50 kilogrammes',
                'actif' => true,
            ],
            [
                'designation' => 'Sel 1Kg',
                'description' => 'Paquet de sel de 1 kilogramme',
                'actif' => true,
            ],
            [
                'designation' => 'Haricot 25Kg',
                'description' => 'Sac de haricots de 25 kilogrammes',
                'actif' => true,
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['designation' => $article['designation']],
                $article
            );
        }
    }
}
