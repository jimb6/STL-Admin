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
                'name' => 'Banay-Banay',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Mati City',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Governor Generoso',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'San Isidro',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Tarragona',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Manay',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Caraga',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Bagangga',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Cateel',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Boston',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('bases')->insert(
            [
                'name' => 'Lupon',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
