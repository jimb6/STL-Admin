<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booth extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['location', 'base_id', 'status'];

    protected $searchableFields = ['*'];

    public function base()
    {
        return $this->belongsTo(Base::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}
