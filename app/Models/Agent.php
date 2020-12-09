<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'age',
        'sex',
        'session_status',
        'base_id',
        'booth_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'sex' => 'boolean',
        'session_status' => 'boolean',
    ];

    protected $hidden = ['password', 'api_token'];

    //  Defining Scopes for Queries

    protected static function booted()
    {
        static::addGlobalScope(new BaseScope);
    }

    public function scopeAgentInBase($query)
    {
        $user = \Auth::user();
        return $query->where('base_id', '=', $user ? $user->base_id:0);
    }

    public function scopeActive($query, $value)
    {
        return $query->where('session_status', $value);
    }


    // Defining all relations

    public function collectionRecords()
    {
        return $this->hasMany(CollectionRecord::class);
    }

    public function transactions()
    {
        return $this->hasMany(BetTransaction::class);
    }

    public function base()
    {
        return $this->belongsTo(Base::class);
    }

    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }
}
