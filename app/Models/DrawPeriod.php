<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawPeriod extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['draw_time', 'draw_type', 'games', 'open_time', 'close_time'];

    protected $searchableFields = ['*'];

    protected $table = 'draw_periods';

    public function scopeCurrentDraw()
    {
        return $this->whereTime('open_time', '<', Carbon::now()->toTimeString())
                    ->whereTime('close_time', '>', Carbon::now()->toTimeString());
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    public function betGames()
    {
        return $this->belongsToMany(Game::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function winningCombination()
    {
        return $this->hasOne(WinningCombination::class);
    }
}
