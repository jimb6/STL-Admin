<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BetScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if( Auth::hasUser() ) {
            $user = Auth::user();
            if (!$user->hasRole('super-admin')) {
                $builder->with('bet_transactions', function ($query) use ($user) {
                    $query->with('users', function ($subQuery) use ($user) {
                        $subQuery->where('cluser_id', '=', $user->cluster_id);
                    });
                });
            }
        }
    }
}
