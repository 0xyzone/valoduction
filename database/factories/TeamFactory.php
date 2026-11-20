<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'short_name' => strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $name) ?: 'VAL', 0, 3)).fake()->unique()->numberBetween(1, 99),
            'logo' => null,
            'primary_color' => fake()->hexColor(),
            'region' => fake()->randomElement(['APAC', 'EMEA', 'Americas', 'CN']),
        ];
    }
}
