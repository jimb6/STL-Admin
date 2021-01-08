<?php

namespace App\Models;

use App\Scopes\AgentScope;
use App\Scopes\ClusterScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends User
{

    protected $table = 'users';

    public static function booted()
    {
        static::addGlobalScope(new AgentScope);
    }

}
