<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
