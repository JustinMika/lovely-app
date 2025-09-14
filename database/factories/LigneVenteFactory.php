<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LigneVente>
 */
class LigneVenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantite = fake()->numberBetween(1, 20);
        $prixAchat = fake()->randomFloat(2, 5, 50);
        $prixUnitaire = $prixAchat * fake()->randomFloat(2, 1.2, 1.8);
        $remiseLigne = fake()->randomFloat(2, 0, $prixUnitaire * $quantite * 0.05); // Max 5% de remise

        return [
            'vente_id' => \App\Models\Vente::factory(),
            'article_id' => \App\Models\Article::factory(),
            'lot_id' => \App\Models\Lot::factory(),
            'quantite' => $quantite,
            'prix_unitaire' => $prixUnitaire,
            'prix_achat' => $prixAchat,
            'remise_ligne' => $remiseLigne,
        ];
    }
}
