<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bet extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

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

//    public function scopeToday($query)
//    {
//        return $query->where()
//    }

    public function scopeBase($query, $value)
    {
        return $query->with('betTransaction.agent', function ($query) use ($value) {
            $query->from('agents')
                ->where('agents.id', 'betTransaction.agent_id')
                ->where('agents.base_id', $value);
        });
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
