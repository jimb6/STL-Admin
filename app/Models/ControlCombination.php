<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlCombination extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['game_id', 'combination', 'max_amount'];
    protected $guard_name = 'sanctum';
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
