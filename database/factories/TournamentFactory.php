<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true).' Cup';

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'status' => 'upcoming',
            'format' => 'single_elimination',
            'starts_at' => now()->addDays(1),
            'ends_at' => now()->addDays(4),
            'logo' => null,
            'description' => fake()->sentence(),
        ];
    }
}
