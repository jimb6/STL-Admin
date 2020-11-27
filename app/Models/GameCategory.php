<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function drawPeriods()
    {
        return $this->belongsToMany('App\Models\DrawPeriod');
    }

    public function DrawResult()
    {
        return $this->belongsTo('App\Models\DrawResult');
    }
}
