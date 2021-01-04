<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Cluster extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['name'];

    protected $searchableFields = ['*'];

    protected $casts = [
    ];

    public function booths()
    {
        return $this->hasMany(Booth::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function agents()
    {
        return $this->hasMany(User::class)
            ->whereHas('roles', function ($query){
                $query->where('name', '=', 'agent');
            });
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class)
            ->with('game');
    }
}
