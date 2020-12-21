<?php

namespace App\Events;

use App\Models\BetTransaction;
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


    public $betTransaction;

    public function __construct(BetTransaction $betTransaction)
    {
        $this->betTransaction = $betTransaction;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bet.transaction.'.$this->betTransaction->user()->cluster_id);
    }

    public function broadcastWith()
    {
        return [

        ];
    }
}
