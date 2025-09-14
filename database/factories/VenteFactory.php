<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vente>
 */
class VenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->randomFloat(2, 50, 1000);
        $remise = fake()->randomFloat(2, 0, $total * 0.1); // Remise max 10%

        return [
            'utilisateur_id' => \App\Models\User::factory(),
            'client_id' => fake()->optional(0.6)->randomElement(\App\Models\Client::pluck('id')->toArray()) ?: \App\Models\Client::factory(),
            'total' => $total,
            'remise_totale' => $remise,
            'montant_paye' => fake()->randomFloat(2, $total - $remise, $total),
        ];
    }
}
