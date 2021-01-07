<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\TransactionBaseScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Ramsey\Uuid\Uuid;

class BetTransaction extends Model implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'qr_code'];

    protected $searchableFields = ['*'];

    protected $table = 'bet_transactions';

    //  Defining Scopes for Queries

    public static function booted()
    {
        static::addGlobalScope(new TransactionBaseScope);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->attributes['qr_code'] = date("mdy").
                '-'.substr(md5(uniqid(mt_rand(), true)), 0, 8);
        });
    }

    public function scopeWithAgent($query)
    {
        return $query->with(['user' => function ($q) {
            $q->agentInBase();
        }]);
    }

    public function scopeBase($query, $baseId)
    {
        return $query->with('user');
    }

    //  Defining All Relationship

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class)->orderBy('amount', 'asc');
    }
}
