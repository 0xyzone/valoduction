<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->name(),
            'riot_id' => fake()->unique()->userName(),
            'riot_tag' => strtoupper(fake()->bothify('??##')),
            'role' => fake()->randomElement(['Duelist', 'Initiator', 'Controller', 'Sentinel', 'Flex']),
            'country' => fake()->countryCode(),
            'avatar' => null,
            'is_active' => true,
        ];
    }
}
