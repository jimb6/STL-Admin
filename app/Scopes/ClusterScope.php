<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClusterScope implements Scope
{


    public function apply(Builder $builder, Model $model)
    {
        if (\Auth::check()){
            $user = \Auth::user();
            $builder->where('cluster_id', '=', $user ? $user->cluster_id:0);
        }
    }
}
