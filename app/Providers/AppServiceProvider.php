<?php

namespace App\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        //
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        $events->listen(function (BuildingMenu $event) {
            // Add some items to the menu...
            $games = \App\Models\GameCategory::all();
            $draws = \App\Models\DrawPeriod::with('gameType')->get();
            $arrayOfGames = [];
            $arrayOfDraws = [];
            foreach ($games as $game) {
                $event->menu->addIn('bets', [
                    'text' => $game->name,
                    'url' => route('game.bets', $game->abbreviation)
                ]);
            }
            foreach ($draws as $draw) {
                $event->menu->addIn('bets', [
                    'text' => $draw->name.' '.substr($draw->gameType->name, 1),
                    'url' => route('game.bets', $draw->name)
                ]);
            }
        }, BuildingMenu::class);


    }
}
