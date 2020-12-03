<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
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
            $games = \App\Models\BetGame::all();
            $draws = \App\Models\DrawPeriod::all();

            foreach ($games as $game) {
                $event->menu->addIn('bets', [
                    'text' => $game->game_name,
                    'url' => route('game.bets', $game->game_abbreviation)
                ]);
            }

//            if(Auth::user()->can(['view bets']))
//            {
                foreach ($draws as $draw) {
                    $event->menu->addIn('draws', [
                        'text' => $draw->draw_type[0].' - '.Carbon::parse($draw->draw_time)->format('g:ia'),
                        'url' => route('game.bets', $draw->draw_time)
                    ]);
                }
//            }

        }, BuildingMenu::class);


    }
}
