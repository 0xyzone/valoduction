<?php

namespace App\Models;

use App\Support\MatchPayload;
use Database\Factories\MatchMapFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['game_match_id', 'map_number', 'map_name', 'team_a_score', 'team_b_score', 'winner_team_id', 'status'])]
class MatchMap extends Model
{
    /** @use HasFactory<MatchMapFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        $dispatch = function (MatchMap $map): void {
            if ($map->gameMatch) {
                MatchPayload::broadcastMatch($map->gameMatch);
            }
        };

        static::saved($dispatch);
        static::deleted($dispatch);
    }

    /**
     * @return BelongsTo<GameMatch, $this>
     */
    public function gameMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    /**
     * @return HasMany<PlayerMatchStat, $this>
     */
    public function playerStats(): HasMany
    {
        return $this->hasMany(PlayerMatchStat::class)->orderByDesc('acs');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'map_number' => 'integer',
            'team_a_score' => 'integer',
            'team_b_score' => 'integer',
        ];
    }
}
