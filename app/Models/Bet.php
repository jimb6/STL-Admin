<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BetScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Bet extends Model implements Auditable
{
    use SoftDeletes, HasFactory, Searchable, \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'combination',
        'amount',
        'is_rumbled',
        'is_voided',
        'bet_transaction_id',
        'draw_period_id',
        'game_id'
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'is_voided' => 'boolean',
        'is_rumbled' => 'boolean',
    ];


//    Defining scope for queries

    public static function booted()
    {
        static::addGlobalScope(new BetScope);
    }

    public function scopeCurrentDraw()
    {
        return $this->whereDate('created_at', Carbon::today())
            ->whereHas('drawPeriod', function ($query) {
                $query
                    ->whereTime('open_time', '<', Carbon::now()->toTimeString())
                    ->whereTime('close_time', '>', Carbon::now()->toTimeString());
            });
    }


    public function getCreatedAtAttribute($time)
    {
        return date("g:i a", strtotime($time));
    }

    public function getUpdatedAtAttribute($time)
    {
        return date("g:i a", strtotime($time));
    }

    public function getWinningPrize()
    {
        return $this->amount * $this->game()->prize;
    }

//    Defining relations

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function betTransaction()
    {
        return $this->belongsTo(BetTransaction::class);
    }

    public function drawPeriod()
    {
        return $this->belongsTo(DrawPeriod::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, BetTransaction::class,);
    }

}
