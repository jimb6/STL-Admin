<?php

namespace Database\Seeders;

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

        DB::table('bet_games')->insert(
            [
                'game_name' => 'STL-2 Digits',
                'game_days' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                ]),
                'game_abbreviation' => 'STL-2D',
                'game_prize' => 70.00,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bet_games')->insert(
            [
                'game_name' => 'STL-3 Digits',
                'game_days' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                ]),
                'game_abbreviation' => 'STL-3D',
                'game_prize' => 450.00,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bet_games')->insert(
            [
                'game_name' => 'STL-4 Digits',
                'game_days' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'game_abbreviation' => 'STL-4D',
                'game_prize' => 450.00,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bet_games')->insert(
            [
                'game_name' => 'Pick 3',
                'game_days' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'game_abbreviation' => 'P3',
                'game_prize' => 450.00,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bet_games')->insert(
            [
                'game_name' => 'STL Pares',
                'game_days' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'game_abbreviation' => 'P',
                'game_prize' => 450.00,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
