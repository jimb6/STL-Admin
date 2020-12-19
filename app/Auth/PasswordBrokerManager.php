<?php


namespace App\Auth;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\PasswordBrokerManager as PasswordBrokerManagerBase;

class PasswordBrokerManager extends PasswordBrokerManagerBase
{

    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $connection = $config['connection'] ?? null;
        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire']
        );
    }

    public function sendResetLink(array $credentials, Closure $callback = null)
    {

    }

    public function reset(array $credentials, Closure $callback)
    {

    }
}
