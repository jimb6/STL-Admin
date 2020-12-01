<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'abbreviation', 'game_configuration_id'
    ];

    public function drawPeriods()
    {
        return $this->belongsToMany('App\Models\DrawPeriod');
    }

    public function drawResult()
    {
        return $this->belongsTo('App\Models\DrawResult');
    }

    public function bets()
    {
        return $this->hasMany('App\Models\BetTransaction');
    }
}
