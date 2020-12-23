<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionBaseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->hasRole(['super-admin'])) {
                $builder->whereDate('created_at', Carbon::today())->with('user', function ($query) use ($user) {
                    $query->where('cluster_id', $user->cluster_id);
                });
            }
        }
    }
}
