<?php

namespace App\Models;

use App\Scopes\StatusScope;

class Agent extends User
{

    protected $table = 'users';

    public static function booted()
    {
        if (!auth()->user()->hasRole(['super-admin'])) {
            static::addGlobalScope(new StatusScope);
        }
    }

}
