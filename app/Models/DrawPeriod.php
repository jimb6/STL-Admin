<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrawPeriod extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['draw_time', 'draw_type'];

    protected $searchableFields = ['*'];

    protected $table = 'draw_periods';

    public function betGames()
    {
        return $this->belongsToMany(BetGame::class);
    }
}
