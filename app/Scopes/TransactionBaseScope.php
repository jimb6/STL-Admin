<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransactionBaseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $user = \Auth::user();
        $builder->whereIn('agent_id', function ($query) use ($user) {
            $query->select('agents.id')
                ->from('agents')
                ->where('base_id', $user->base_id);
        });
    }
}
