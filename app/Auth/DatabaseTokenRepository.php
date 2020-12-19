<?php


namespace App\Auth;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as DatabaseTokenRepositoryBase;

class DatabaseTokenRepository extends DatabaseTokenRepositoryBase
{
    public function create(CanResetPasswordContract $user)
    {
        $email = $user->getEmailForPasswordReset();
        $mobile = $user->getMobileForPasswordReset();
        $this->deleteExisting($user);
        $token = $this->createNewToken();
        $this->getTable()->insert($this->getPayload($email, $mobile, $token));
        return $token;
    }

    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere("mobile", $user->getMobileForPasswordReset())
            ->delete();
    }

    protected function getPayload($email, $mobile, $token)
    {
        return ['email' => $email, 'mobile' => $mobile, 'token' => $this->hasher->make($token), 'created_at' => new Carbon];
    }

    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere("mobile", $user->getMobileForPasswordReset())
            ->first();
        return $record &&
            ! $this->tokenExpired($record['created_at']) &&
            $this->hasher->check($token, $record['token']);
    }
}
