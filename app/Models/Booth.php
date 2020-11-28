<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booth extends Model
{
    use HasFactory;

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }
}
