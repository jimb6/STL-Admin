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

    protected $fillable = ['address_id', 'base_id', 'user_id'];

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

    public function  address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
