<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameMatch>
 */
class GameMatchFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tournament_id' => Tournament::factory(),
            'team_a_id' => Team::factory(),
            'team_b_id' => Team::factory(),
            'best_of' => 3,
            'status' => 'scheduled',
            'round_label' => 'Quarterfinal',
            'scheduled_at' => now()->addHours(2),
            'team_a_score' => 0,
            'team_b_score' => 0,
            'winner_team_id' => null,
        ];
    }
}
