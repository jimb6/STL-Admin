<?php

namespace App\Events;

use App\Models\Device;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDeviceAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }


    public function broadcastOn()
    {
        return new Channel('device-store.'.$this->device->cluster_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->device->id,
            'serial_number' => $this->device->serial_number,
            'updated_at' => $this->device->updated_at,
            'user' => [
                'name' => $this->device->user,
            ]
        ];
    }

}
