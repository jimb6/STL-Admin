<?php

namespace App\Models;

use App\Scopes\StatusScope;
use Illuminate\Support\Facades\Auth;

class Agent extends User
{

    protected $table = 'users';

    public static function booted()
    {
        if (Auth::check() && !Auth::user()->hasRole(['super-admin'])) {
            static::addGlobalScope(new StatusScope);
        }
    }

}
