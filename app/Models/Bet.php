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
        'voided',
        'rumbled',
        'combination',
        'amount',
        'bet_game_id',
        'bet_transaction_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'voided' => 'boolean',
        'rumbled' => 'boolean',
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

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }

    public function betGame()
    {
        return $this->belongsTo(BetGame::class);
    }

    public function betTransaction()
    {
        return $this->belongsTo(BetTransaction::class);
    }

    public function drawPeriod()
    {
        return $this->belongsTo(DrawPeriod::class);
    }

    public function agent()
    {
        return $this->hasOneThrough(Agent::class, BetTransaction::class,);
    }
}
