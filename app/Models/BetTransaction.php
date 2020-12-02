<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
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

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
