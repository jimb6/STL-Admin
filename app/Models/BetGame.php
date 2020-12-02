<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BetGame extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'game_name',
        'game_days',
        'game_abbreviation',
        'game_prize',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'bet_games';

    protected $casts = [
        'game_days' => 'array',
    ];

    public function drawResult()
    {
        return $this->hasOne(DrawResult::class);
    }

    public function closeNumbers()
    {
        return $this->hasMany(CloseNumber::class);
    }

    public function bet()
    {
        return $this->hasMany(Bet::class);
    }

    public function drawPeriods()
    {
        return $this->belongsToMany(DrawPeriod::class);
    }
}
