<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Winner extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['bet_id', 'draw_result_id'];

    protected $searchableFields = ['*'];


    public function bet()
    {
        return $this->belongsTo(Bet::class);
    }

    public function drawResult()
    {
        return $this->belongsTo(DrawResult::class);
    }
}
