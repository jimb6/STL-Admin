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
        'min_per_field_set',
        'max_per_field_set',
    ];



    protected $casts = [
        'is_closed' => 'boolean'
    ];


    public function scopeCurrentDrawDate()
    {
        return $this->whereDate('created_at', Carbon::today());
    }

    public function scopeCurrentDrawTime()
    {
        return $this
            ->whereHas('drawPeriods', function ($query) {
                $query
                    ->whereTime('open_time', '<', Carbon::now()->toTimeString())
                    ->whereTime('close_time', '>', Carbon::now()->toTimeString());
            });
    }


    public function gameConfiguration()
    {
        return $this->hasOne(GameConfiguration::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
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

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

}
