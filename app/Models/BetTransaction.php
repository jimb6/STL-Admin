<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\TransactionBaseScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BetTransaction extends Model implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'qr_code', 'printable', 'void_status'];

    protected $searchableFields = ['*'];

    protected $table = 'bet_transactions';

    protected $casts = [
        'printable' => 'boolean'
    ];

    //  Defining Scopes for Queries

    public static function booted()
    {
        $user = auth()->user();
        if (!$user->hasRole(['super-admin'])) {
            static::addGlobalScope(new TransactionBaseScope);
        }

    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->attributes['qr_code'] = date("mdy") .
                '-' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
        });
        static::created(function (Model $model) {
            $model->attributes['qr_code'] = $model->attributes['id'] . '-' . $model->attributes['qr_code'];
        });
    }

    public function scopeToday($query)
    {
        $query->whereDate('created_at', Carbon::today());
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
        return $this->belongsTo(User::class)->with('device', 'cluster.commissions');
    }

    public function bets()
    {
        return $this->hasMany(Bet::class)->with('drawPeriod', 'game')
            ->orderBy('amount', 'asc');
    }
}
