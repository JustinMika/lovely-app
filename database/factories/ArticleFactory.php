<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'designation' => fake()->randomElement([
                'Riz 25Kg',
                'Riz 50Kg',
                'Huile 1L',
                'Huile 5L',
                'Farine 25Kg',
                'Sucre 50Kg',
                'Sel 1Kg',
                'Haricot 25Kg',
                'Maïs 50Kg',
                'Lait en poudre 400g'
            ]),
            'description' => fake()->sentence(),
            'actif' => fake()->boolean(90), // 90% de chance d'être actif
        ];
    }
}
