<?php

namespace App\Models;

use App\Scopes\DrawPeriodStatus;
use App\Scopes\ClusterScope;
use App\Scopes\StatusScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends User
{

    protected $table = 'users';

    public static function booted()
    {
        static::addGlobalScope(new StatusScope);
    }

}
