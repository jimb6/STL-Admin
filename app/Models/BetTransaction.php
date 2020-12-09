<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
use App\Scopes\TransactionBaseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BetTransaction extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['agent_id'];

    protected $searchableFields = ['*'];

    protected $table = 'bet_transactions';


    //  Defining Scopes for Queries

    public static function booted()
    {
        static::addGlobalScope(new TransactionBaseScope);
    }

    public function scopeWithAgent($query)
    {
        return $query->with(['agent' => function($q)
        {
            $q->agentInBase();
        }]);
    }

    public function scopeBase($query, $baseId)
    {
        return $query->with('agent');
    }


    //  Defining All Relationship

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
