<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\MatchVeto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchVeto>
 */
class MatchVetoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_match_id' => GameMatch::factory(),
            'step_order' => 1,
            'team_id' => null,
            'action' => 'ban',
            'map_name' => 'Ascent',
            'side' => null,
        ];
    }
}
