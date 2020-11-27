<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawResult extends Model
{
    use HasFactory;

    public function drawPeriod()
    {
        return $this->hasOne('App\Models\Period');
    }

    public function gameCategory()
    {
        return $this->hasOne('App\Models\GameCategory');
    }
}
