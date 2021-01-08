<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewActiveAgent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $agent;

    public function __construct(User $agent)
    {
        $this->agent = $agent;
    }

    public function broadcastOn()
    {
        return new Channel('dashboard-event');
    }

    public function broadcastWith()
    {
        return [
        ];
    }
}
