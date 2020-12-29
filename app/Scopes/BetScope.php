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
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole(['super-admin'])) {
            } else if ($user->hasRole('admin')) {
                $builder->with(['betTransaction' => function ($query) use ($user) {
                    $query->where('cluster_id', '=', $user->cluster_id);
                }]);
            } else {
                $builder->with(['betTransaction' => function ($query) use ($user) {
                    $query->where('user_id', '=', $user->id);
                }]);
            }
        }
    }
}
