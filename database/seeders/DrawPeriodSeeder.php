<?php

namespace Database\Seeders;

use App\Models\DrawPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DrawPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromTimeString(Carbon::createFromFormat('g:i A', '10:30 AM')),
                'draw_type' => 'Local',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromTimeString(Carbon::createFromFormat('g:i A', '3:00 PM')),
                'draw_type' => 'Local',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromTimeString(Carbon::createFromFormat('g:i A', '7:00 PM')),
                'draw_type' => 'Local',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromFormat('g:ia', '2:00pm'),
                'draw_type' => 'National',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromFormat('g:ia', '5:00pm'),
                'draw_type' => 'National',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('draw_periods')->insert(
            [
                'draw_time' => Carbon::createFromFormat('g:ia', '9:00pm'),
                'draw_type' => 'National',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
