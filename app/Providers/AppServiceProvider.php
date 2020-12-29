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
                 if ($role['name'] != 'agent'){
                     return [
                        'text' => strtoupper($role['name']),
                        'route' => ['users.index', ['role' => $role['name']]],
                        'active' => ['admin/users/'.$role['name']]
                    ];
                }
            });
            $event->menu->addIn('users', ...$items);
            $icons = ['fas fa-dice-one', 'fas fa-dice-two', 'fas fa-dice-three', 'fas fa-dice-four', 'fas fa-dice-five', 'fas fa-dice-six'];
            $gameItem = Game::all()->map(function (Game $game) use ($icons) {
                return [
                    'text' => strtoupper($game['abbreviation']),
                    'route' => ['games.abbreviation.config', ['abbreviation' => $game['abbreviation']]],
                    'active' => ['admin/games/categorize/'.$game['abbreviation']],
                    'icon' => 'fas fa-dice-five',
                    'icon_color' => 'success',
                ];
            });

            $event->menu->addIn('games', ...$gameItem);
        });
    }
}
