<?php

namespace App\Policies;

use App\Models\BetTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BetTransactionPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->can('list-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to view any bet transactions.', '');
    }


    public function view(User $user, BetTransaction $betTransaction)
    {
        return $user->can('view-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to view bet transactions.', '');
    }


    public function create(User $user)
    {
        return $user->can('create-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to create bet transactions.', '');
    }


    public function update(User $user, BetTransaction $betTransaction)
    {
        return $user->can('update-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to update bet transactions.', '');
    }


    public function delete(User $user, BetTransaction $betTransaction)
    {
        return $user->can('delete-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to delete bet transactions.', '');
    }

    public function restore(User $user, BetTransaction $betTransaction)
    {
        return $user->can('restore-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to restore bet transactions.', '');

    }

    public function forceDelete(User $user, BetTransaction $betTransaction)
    {
        return $user->can('force-delete-bet-transactions')
            ? Response::allow()
            : Response::deny('You do not have permission to delete bet transactions.', '');
    }
}
