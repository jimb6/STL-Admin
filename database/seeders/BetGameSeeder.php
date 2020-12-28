<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameConfiguration;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BetGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $game = Game::create([
                'description' => 'STL-2 Digits',
                'abbreviation' => 'STL-2D',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 1,
            'digit_per_field_set' => 2,
            'min_per_field_set' => 0,
            'max_per_field_set' => 99,
            'has_repetition' => true,
            'is_rumbled' => true,
            'max_sum_bet' => 1000,
            'multiplier' =>  70,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);

        $game = Game::create(
            [
                'description' => 'STL-3 Digits',
                'abbreviation' => 'STL-3D',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 1,
            'digit_per_field_set' => 3,
            'has_repetition' => true,
            'min_per_field_set' => 0,
            'max_per_field_set' => 999,
            'is_rumbled' => true,
            'max_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);

        $game = Game::create(
            [
                'description' => 'STL-Pares',
                'abbreviation' => 'STL-Pares',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 3,
            'digit_per_field_set' => 2,
            'min_per_field_set' => 1,
            'max_per_field_set' => 40,
            'has_repetition' => false,
            'is_rumbled' => false,
            'max_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);

        $game = Game::create(
            [
                'description' => 'National-2 Digits',
                'abbreviation' => 'S2',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 1,
            'digit_per_field_set' => 2,
            'min_per_field_set' => 0,
            'max_per_field_set' => 99,
            'has_repetition' => true,
            'is_rumbled' => true,
            'max_sum_bet' => 1000,
            'multiplier' =>  70,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);

        $game = Game::create(
            [
                'description' => 'National-3 Digits',
                'abbreviation' => 'S3',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 1,
            'digit_per_field_set' => 3,
            'min_per_field_set' => 0,
            'max_per_field_set' => 999,
            'has_repetition' => true,
            'is_rumbled' => true,
            'max_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);


        $game = Game::create(
            [
                'description' => 'National-4 Digits',
                'abbreviation' => 'S4',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 1,
            'digit_per_field_set' => 4,
            'min_per_field_set' => 0,
            'max_per_field_set' => 9999,
            'has_repetition' => false,
            'is_rumbled' => false,
            'max_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);


        $game = Game::create(
            [
                'description' => 'Pick 3',
                'abbreviation' => 'P3',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        GameConfiguration::create([
            'game_id'   => $game->id,
            'field_set' => 3,
            'digit_per_field_set' => 2,
            'min_per_field_set' => 1,
            'max_per_field_set' => 49,
            'has_repetition' => false,
            'is_rumbled' => false,
            'max_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'min_per_bet' => 5,

        ]);


    }
}
