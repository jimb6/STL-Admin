<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawPeriod extends Model
{
    use HasFactory;

    public function gameCategories()
    {
        return $this->hasMany('App\Models\GameCategory');
    }
}
