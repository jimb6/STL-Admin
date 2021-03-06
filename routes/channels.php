<?php

use App\Models\Device;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//Broadcast::channel('devices.{device}', \App\Broadcasting\DeviceChannel::class);

Broadcast::channel('device-store.{cluster_id}', function ($user, $cluster_id) {
    if ($user->hasRole('Super-Admin'))
        return true;
    return $user->cluster_id == $cluster_id;
//    return true;ssSSS
}, ['guards' => ['web']]);

Broadcast::channel('active.agent', function ($user) {
        return true;
}, ['guards' => ['web']]);

Broadcast::channel('bet.transaction.{cluster_id}', function ($user, $cluster_id) {
    if ($user->hasRole('Super-Admin'))
        return true;
    return $user->cluster == $cluster_id;
//    return true;
}, ['guards' => ['web']]);
