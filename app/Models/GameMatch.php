<?php

namespace App\Models;

use App\Support\MatchPayload;
use Database\Factories\GameMatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tournament_id',
    'team_a_id',
    'team_b_id',
    'best_of',
    'status',
    'round_label',
    'scheduled_at',
    'team_a_score',
    'team_b_score',
    'winner_team_id',
])]
class GameMatch extends Model
{
    /** @use HasFactory<GameMatchFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn (GameMatch $match) => MatchPayload::broadcastMatch($match));
    }

    /**
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function teamA(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function teamB(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    /**
     * @return HasMany<MatchVeto, $this>
     */
    public function vetos(): HasMany
    {
        return $this->hasMany(MatchVeto::class)->orderBy('step_order');
    }

    /**
     * @return HasMany<MatchMap, $this>
     */
    public function maps(): HasMany
    {
        return $this->hasMany(MatchMap::class)->orderBy('map_number');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'best_of' => 'integer',
            'scheduled_at' => 'datetime',
            'team_a_score' => 'integer',
            'team_b_score' => 'integer',
        ];
    }
}
