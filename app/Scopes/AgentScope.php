<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AgentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
            $builder->whereHas('roles', function ($query){
                $query->where('name', '=', 'agent');
            });
    }
}
