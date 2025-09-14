<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lot>
 */
class LotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantiteInitiale = fake()->numberBetween(50, 500);
        $prixAchat = fake()->randomFloat(2, 10, 100);

        return [
            'approvisionnement_id' => \App\Models\Approvisionnement::factory(),
            'article_id' => \App\Models\Article::factory(),
            'ville_id' => \App\Models\Ville::factory(),
            'numero_lot' => 'LOT-' . fake()->unique()->numerify('####'),
            'quantite_initiale' => $quantiteInitiale,
            'quantite_restante' => fake()->numberBetween(0, $quantiteInitiale),
            'prix_achat' => $prixAchat,
            'prix_vente' => $prixAchat * fake()->randomFloat(2, 1.2, 1.8), // Marge de 20% à 80%
            'date_arrivee' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
