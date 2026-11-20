<?php

namespace App\Events;

use App\Models\GameMatch;
use App\Support\MatchPayload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameMatch $gameMatch) {}

    /**
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("matches.{$this->gameMatch->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.updated';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'match' => MatchPayload::forMatch($this->gameMatch),
        ];
    }
}
