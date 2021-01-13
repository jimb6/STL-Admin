<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BetScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            //Filter based on clusters bets from agents
            $builder->with(['betTransaction' => function ($query) use ($user) {
                $query->whereHas('user', function ($subQuery) use ($user) {
                    $subQuery->where('cluster_id', '=', $user->cluster_id);
                });
            }]);
        } else {
            //filter based on own bets
            $builder->with(['betTransaction' => function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            }]);
        }

    }
}
