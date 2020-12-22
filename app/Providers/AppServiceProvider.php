<?php

namespace App\Providers;

use App\Models\Bet;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use function Livewire\str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $items = Role::all()->map(function (Role $role) {
                return [
                    'text' => strtoupper($role['name']),
                    'url' => route('users.index', $role['name']),
                ];
            });
            $event->menu->addIn('users', ...$items);

            $gameItem = Game::all()->map(function (Game $game){
                return [
                    'text' => strtoupper($game['abbreviation']),
                    'url' => route('bets.index', $game['abbreviation'])
                ];
            });

            $event->menu->addIn('bets', ...$gameItem);
        });
    }
}
