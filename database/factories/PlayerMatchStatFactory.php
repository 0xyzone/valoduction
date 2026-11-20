<?php

namespace Database\Factories;

use App\Models\MatchMap;
use App\Models\Player;
use App\Models\PlayerMatchStat;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerMatchStat>
 */
class PlayerMatchStatFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'match_map_id' => MatchMap::factory(),
            'player_id' => Player::factory(),
            'team_id' => Team::factory(),
            'agent' => 'Jett',
            'acs' => fake()->numberBetween(140, 320),
            'kills' => fake()->numberBetween(10, 28),
            'deaths' => fake()->numberBetween(8, 22),
            'assists' => fake()->numberBetween(2, 12),
            'adr' => fake()->randomFloat(2, 95, 210),
            'hs_percentage' => fake()->randomFloat(2, 12, 42),
            'first_kills' => fake()->numberBetween(0, 6),
            'first_deaths' => fake()->numberBetween(0, 5),
            'credits_spent' => fake()->numberBetween(32000, 65000),
        ];
    }
}
