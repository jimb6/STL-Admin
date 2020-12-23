<?php


namespace App\Scopes;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->hasRole(['super-admin'])) {
                $builder->whereDate('created_at', Carbon::today())
                    ->with(['betTransaction' => function($query) use ($user) {
                        $query->where('user_id', '=', $user->id);
                    }]);
            }
        }
    }
}
