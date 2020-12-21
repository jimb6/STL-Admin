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
        return new Channel('active.agent');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->agent->id,
            'name' => $this->agent->name,
            'birthdate' => $this->agent->birthdate,
            'gender' => $this->agent->gender,
            'contact_number' =>  $this->agent->contact_number,
            'email' =>  $this->agent->email,
            'cluster_id' => $this->agent->cluster_id,
        ];
    }
}
