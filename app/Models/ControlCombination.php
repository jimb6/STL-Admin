<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlCombination extends Model
{
    use HasFactory, Searchable;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
