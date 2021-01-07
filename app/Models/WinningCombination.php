<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinningCombination extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["combination", "game_id", "draw_period_id", "verified_at" ];

    protected $searchableFields = ['*'];    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function drawPeriod()
    {
        return $this->belongsTo(DrawPeriod::class);
    }
}
