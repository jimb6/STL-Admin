<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TransactionBaseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {

        if (Auth::hasUser()) {
            $user = Auth::user();
            if (!$user->hasRole(['Super-Admin'])) {
                $builder->with('user', function ($query) use ($user) {
                    $query->where('cluster_id', 2);
                });
            }
        }
    }
}
