<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransactionBaseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();
        $builder->whereHas('user', function ($query) use ($user) {
            $query->where('cluster_id', $user->cluster_id);
        })->with('user');
    }
}
