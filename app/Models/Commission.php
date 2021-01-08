<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;


    protected $fillable = ['cluster_id', 'game_id','commission_rate'];

    protected $searchableFields = ['*'];

    protected $casts = [
    ];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
