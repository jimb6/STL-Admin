<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrawResult extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['bet_game_id'];

    protected $searchableFields = ['*'];

    protected $table = 'draw_results';

    public function betGame()
    {
        return $this->belongsTo(BetGame::class);
    }

    public function winners()
    {
        return $this->hasMany(Winner::class);
    }
}
