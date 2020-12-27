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
            'has_repetition' => true,
            'is_rumbled' => true,
            'min_sum_bet' => 1000,
            'multiplier' =>  70,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ]),
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
            'is_rumbled' => true,
            'min_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ]),
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
            'has_repetition' => true,
            'is_rumbled' => true,
            'min_sum_bet' => 1000,
            'multiplier' =>  70,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
            ]),
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
            'has_repetition' => true,
            'is_rumbled' => true,
            'min_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ]),
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
            'has_repetition' => false,
            'is_rumbled' => false,
            'min_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
            ]),
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
            'has_repetition' => false,
            'is_rumbled' => false,
            'min_sum_bet' => 1000,
            'multiplier' =>  500,
            'transaction_limit' => 10,
            'max_per_bet' => 500,
            'days_availability' => json_encode([
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ]),
        ]);


    }
}
