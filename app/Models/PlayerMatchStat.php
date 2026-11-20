<?php

namespace App\Models;

use App\Support\MatchPayload;
use Database\Factories\PlayerMatchStatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'match_map_id',
    'player_id',
    'team_id',
    'agent',
    'acs',
    'kills',
    'deaths',
    'assists',
    'adr',
    'hs_percentage',
    'first_kills',
    'first_deaths',
    'credits_spent',
])]
class PlayerMatchStat extends Model
{
    /** @use HasFactory<PlayerMatchStatFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        $dispatch = function (PlayerMatchStat $stat): void {
            if ($stat->matchMap?->gameMatch) {
                MatchPayload::broadcastMatch($stat->matchMap->gameMatch);
            }
        };

        static::saved($dispatch);
        static::deleted($dispatch);
    }

    /**
     * @return BelongsTo<MatchMap, $this>
     */
    public function matchMap(): BelongsTo
    {
        return $this->belongsTo(MatchMap::class);
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acs' => 'integer',
            'kills' => 'integer',
            'deaths' => 'integer',
            'assists' => 'integer',
            'adr' => 'float',
            'hs_percentage' => 'float',
            'first_kills' => 'integer',
            'first_deaths' => 'integer',
            'credits_spent' => 'integer',
        ];
    }
}
