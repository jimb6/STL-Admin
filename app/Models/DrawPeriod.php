<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawPeriod extends Model
{
    use HasFactory;

    public function bets()
    {
        return $this->hasMany('App\Models\BetTransaction');
    }

    public function gameType()
    {
        return $this->belongsTo('App\Models\GameType', 'game_types_id', 'id');
    }
}
