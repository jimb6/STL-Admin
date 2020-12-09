<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BaseScope implements Scope
{


    public function apply(Builder $builder, Model $model)
    {
        if (\Auth::check()){
            $user = \Auth::user();
            $builder->where('base_id', '=', $user ? $user->base_id:0);
        }
    }
}
