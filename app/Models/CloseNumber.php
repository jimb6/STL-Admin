<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CloseNumber extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['combination', 'game_id', 'draw_period_id'];

    protected $searchableFields = ['*'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function drawPeriod()
    {
        return $this->belongsTo(DrawPeriod::class);
    }

}
