<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
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

    protected static function booted()
    {
        static::addGlobalScope(new BaseScope);
    }

    public function scopeActive($query, $value)
    {
        return $query->where('status', $value);
    }

    public function base()
    {
        return $this->belongsTo(Base::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}
