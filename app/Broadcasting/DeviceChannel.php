<?php

namespace App\Broadcasting;

use App\Models\Device;
use App\Models\User;

class DeviceChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user, Device $device)
    {
        return $user->cluster_id === $device->cluster_id;
    }
}
