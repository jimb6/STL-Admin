<?php

namespace Database\Seeders;

use App\Models\Base;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('bases')->insert(
            [
                'base_name' => 'Banay-Banay',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Mati City',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Governor Generoso',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'San Isidro',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Tarragona',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Manay',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Caraga',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Bagangga',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Cateel',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Boston',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'base_name' => 'Lupon',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
