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
        if (Auth::hasUser()) {
            $user = Auth::user();
            if (!$user->hasRole(['Super-Admin'])) {
                $builder->with(['betTransaction' => function($query) use ($user) {
                    $query->where('user_id', '=', $user->id);
                }]);
            }
        }
    }
}
