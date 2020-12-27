<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BetGameDrawPeriodFactory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        //
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 1,
                'draw_period_id' => 1
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 2,
                'draw_period_id' => 1
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 7,
                'draw_period_id' => 1
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 1,
                'draw_period_id' => 2
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 2,
                'draw_period_id' => 2
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 7,
                'draw_period_id' => 2
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 1,
                'draw_period_id' => 3
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 2,
                'draw_period_id' => 3
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 7,
                'draw_period_id' => 3
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 3,
                'draw_period_id' => 4
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 4,
                'draw_period_id' => 4
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 3,
                'draw_period_id' => 5
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 4,
                'draw_period_id' => 5
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 3,
                'draw_period_id' => 6
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 4,
                'draw_period_id' => 6
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 35,
                'draw_period_id' => 6
            ],
        );
        DB::table('draw_period_game')->insert(
            [
                'game_id' => 6,
                'draw_period_id' => 6
            ],
        );
    }
}
