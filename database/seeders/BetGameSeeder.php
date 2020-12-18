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

        DB::table('games')->insert(
            [
                'description' => 'STL-2 Digits',
                'days_availability' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                ]),
                'abbreviation' => 'STL-2D',
                'prize' => 70.00,
                'field_set' => 1,
                'digit_per_field_set' => 2,
                'min_number' => 0,
                'max_number' => 99,
                'has_repetition' => true,

                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('games')->insert(
            [
                'description' => 'STL-3 Digits',
                'days_availability' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                ]),
                'abbreviation' => 'STL-3D',
                'prize' => 500.00,
                'field_set' => 1,
                'digit_per_field_set' => 3,
                'min_number' => 0,
                'max_number' => 999,
                'has_repetition' => true,

                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('games')->insert(
            [
                'description' => 'STL-4 Digits',
                'days_availability' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'abbreviation' => 'STL-4D',
                'prize' => 500.00,
                'field_set' => 1,
                'digit_per_field_set' => 4,
                'min_number' => 0,
                'max_number' => 9999,
                'has_repetition' => true,

                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('games')->insert(
            [
                'description' => 'Pick 3',
                'days_availability' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'abbreviation' => 'P3',
                'prize' => 450.00,
                'field_set' => 3,
                'digit_per_field_set' => 2,
                'min_number' => 1,
                'max_number' => 58,
                'has_repetition' => false,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('games')->insert(
            [
                'description' => 'STL Pares',
                'days_availability' => json_encode([
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ]),
                'abbreviation' => 'P',
                'prize' => 450.00,
                'field_set' => 2,
                'digit_per_field_set' => 2,
                'min_number' => 1,
                'max_number' => 40,
                'has_repetition' => false,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
