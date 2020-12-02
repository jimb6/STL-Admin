<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CloseNumber extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['number_value', 'bet_game_id'];

    protected $searchableFields = ['*'];

    protected $table = 'close_numbers';

    public function betGame()
    {
        return $this->belongsTo(BetGame::class);
    }
}
