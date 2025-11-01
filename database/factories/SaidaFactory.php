<?php

namespace Database\Factories;

use App\Models\Conta;
use App\Models\InstitutionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Saida>
 */
class SaidaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'conta_id' => Conta::all()->random()->id,
            'title' => fake()->words(4, true),
            'category' => fake()->words(1, true),
            'payment_method' => fake()->randomElement(['P','C','D','B','K' ])  ,
            'amount' => fake()->randomFloat(2, 50, 500),
        ];
    }
}
