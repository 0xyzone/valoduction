<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['team_id', 'name', 'riot_id', 'riot_tag', 'role', 'country', 'avatar', 'is_active'])]
class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasMany<PlayerMatchStat, $this>
     */
    public function matchStats(): HasMany
    {
        return $this->hasMany(PlayerMatchStat::class);
    }

    /**
     * Get the player's full Riot IGN (RiotID#TAG).
     */
    public function getFullRiotIdAttribute(): string
    {
        return "{$this->riot_id}#{$this->riot_tag}";
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
