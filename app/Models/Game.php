<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'description',
        'abbreviation',
        'prize',
        'field_set',
        'digit_per_field_set',
        'min_number',
        'max_number',
        'has_repetition',
        'is_rumbled',
        'days_availability',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'is_closed' => 'boolean'
    ];


    public function gameConfiguration()
    {
        return $this->hasOne(GameConfiguration::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class)
            ->whereDate('created_at', Carbon::today())
            ->whereHas('drawPeriod', function ($query) {
                $query
                    ->whereTime('open_time', '<', Carbon::now()->toTimeString())
                    ->whereTime('close_time', '>', Carbon::now()->toTimeString());
            });
    }

    public function drawPeriods()
    {
        return $this->belongsToMany(DrawPeriod::class);
    }

    public function controlCombination()
    {
        return $this->hasMany(ControlCombination::class);
    }

    public function winningCombination()
    {
        return $this->hasOne(WinningCombination::class);
    }

}
