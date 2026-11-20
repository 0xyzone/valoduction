<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\MatchMap;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchMap>
 */
class MatchMapFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_match_id' => GameMatch::factory(),
            'map_number' => 1,
            'map_name' => 'Ascent',
            'team_a_score' => 13,
            'team_b_score' => 9,
            'winner_team_id' => null,
            'status' => 'completed',
        ];
    }
}
