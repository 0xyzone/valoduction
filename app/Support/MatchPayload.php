<?php

namespace App\Support;

use App\Events\MatchUpdated;
use App\Events\VetoUpdated;
use App\Models\GameMatch;
use App\Models\MatchMap;
use App\Models\MatchVeto;
use App\Models\PlayerMatchStat;
use App\Models\Team;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MatchPayload
{
    public static function broadcastMatch(GameMatch $match): void
    {
        try {
            MatchUpdated::dispatch($match);
        } catch (Throwable $exception) {
            Log::warning('Failed to broadcast MatchUpdated event.', [
                'game_match_id' => $match->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public static function broadcastVeto(GameMatch $match): void
    {
        try {
            VetoUpdated::dispatch($match);
        } catch (Throwable $exception) {
            Log::warning('Failed to broadcast VetoUpdated event.', [
                'game_match_id' => $match->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Build the full serializable payload for a GameMatch (for OBS overlays and Reverb broadcasts).
     *
     * @return array<string, mixed>
     */
    public static function forMatch(GameMatch $match): array
    {
        $match->loadMissing([
            'tournament',
            'teamA',
            'teamB',
            'winner',
            'vetos.team',
            'maps.winner',
            'maps.playerStats.player',
            'maps.playerStats.team',
        ]);

        return [
            'id' => $match->id,
            'status' => $match->status,
            'best_of' => $match->best_of,
            'round_label' => $match->round_label,
            'scheduled_at' => $match->scheduled_at?->toIso8601String(),
            'team_a_score' => $match->team_a_score,
            'team_b_score' => $match->team_b_score,
            'winner_team_id' => $match->winner_team_id,
            'tournament' => [
                'id' => $match->tournament->id,
                'name' => $match->tournament->name,
                'slug' => $match->tournament->slug,
                'logo_url' => $match->tournament->logo ? Storage::disk('public')->url($match->tournament->logo) : null,
            ],
            'team_a' => self::formatTeam($match->teamA),
            'team_b' => self::formatTeam($match->teamB),
            'vetos' => $match->vetos->map(fn (MatchVeto $veto): array => [
                'id' => $veto->id,
                'step_order' => $veto->step_order,
                'action' => $veto->action,
                'map_name' => $veto->map_name,
                'side' => $veto->side,
                'team' => $veto->team ? self::formatTeam($veto->team) : null,
            ])->values()->all(),
            'maps' => $match->maps->map(fn (MatchMap $map): array => [
                'id' => $map->id,
                'map_number' => $map->map_number,
                'map_name' => $map->map_name,
                'team_a_score' => $map->team_a_score,
                'team_b_score' => $map->team_b_score,
                'winner_team_id' => $map->winner_team_id,
                'status' => $map->status,
                'stats' => $map->playerStats->map(fn (PlayerMatchStat $stat): array => [
                    'id' => $stat->id,
                    'player_id' => $stat->player_id,
                    'team_id' => $stat->team_id,
                    'player_name' => $stat->player->name,
                    'riot_id' => $stat->player->full_riot_id,
                    'agent' => $stat->agent,
                    'acs' => $stat->acs,
                    'kills' => $stat->kills,
                    'deaths' => $stat->deaths,
                    'assists' => $stat->assists,
                    'adr' => $stat->adr,
                    'hs_percentage' => $stat->hs_percentage,
                    'first_kills' => $stat->first_kills,
                    'first_deaths' => $stat->first_deaths,
                    'credits_spent' => $stat->credits_spent,
                ])->values()->all(),
            ])->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function formatTeam(Team $team): array
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'short_name' => $team->short_name,
            'primary_color' => $team->primary_color ?: '#FF4655',
            'logo_url' => $team->logo ? Storage::disk('public')->url($team->logo) : null,
        ];
    }
}
