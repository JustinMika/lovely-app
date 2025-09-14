<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Approvisionnement>
 */
class ApprovisionnementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'fournisseur' => fake()->optional(0.8)->company(), // 80% ont un fournisseur spécifié
            'utilisateur_id' => \App\Models\User::factory(),
        ];
    }
}
