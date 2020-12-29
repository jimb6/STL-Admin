<?php

namespace App\Events;

use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\CloseNumber;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBetTransactionAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public $betTransaction;

    public function broadcastOn()
    {
        return new Channel('bets.'.$this->game->abbreviation);
    }

    public function broadcastWith()
    {
        return [
//            'game' => $this->game->id,
        ];
    }
}
