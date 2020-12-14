<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'description',
        'abbreviation',
        'prize',
        'field_set',
        'digit_per_field_set',
        'min_number',
        'max_number',
        'has_repetition',
        'days_availability',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'days_availability' => 'array',
        'has_repetition' => 'boolean'
    ];


    public function bet()
    {
        return $this->hasMany(Bet::class);
    }


}
