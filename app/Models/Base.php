<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Base extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['base_name'];

    protected $searchableFields = ['*'];

    public function booths()
    {
        return $this->hasMany(Booth::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
