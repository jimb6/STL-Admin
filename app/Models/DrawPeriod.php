<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawPeriod extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['draw_time', 'draw_type'];

    protected $searchableFields = ['*'];

    protected $table = 'draw_periods';

    public function betGames()
    {
        return $this->belongsToMany(BetGame::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
