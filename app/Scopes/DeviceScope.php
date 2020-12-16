<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class DeviceScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if( Auth::hasUser() ) {
            $user = Auth::user();
            $builder->where('user_id', '!=', null)->with('user');
        }
    }
}
