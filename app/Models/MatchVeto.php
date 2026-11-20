<?php

namespace App\Models;

use App\Support\MatchPayload;
use Database\Factories\MatchVetoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['game_match_id', 'step_order', 'team_id', 'action', 'map_name', 'side'])]
class MatchVeto extends Model
{
    /** @use HasFactory<MatchVetoFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        $dispatch = function (MatchVeto $veto): void {
            if ($veto->gameMatch) {
                MatchPayload::broadcastVeto($veto->gameMatch);
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
            'step_order' => 'integer',
        ];
    }
}
