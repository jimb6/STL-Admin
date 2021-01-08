<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    protected $casts = [
        'id' => 'string',
        'last_activity' => 'datetime',
    ];

    protected $table = 'sessions';

    public static function booted()
    {
        parent::booted();
        static::addGlobalScope('LastActivityScope', function (Builder $builder){
            $builder->orderBy('last_activity');
        });
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('last_activity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
