<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clusters')->insert(
            [
                'name' => 'Banay-Banay',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Mati',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Governor Generoso',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'San Isidro',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Tarragona',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Manay',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Caraga',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Bagangga',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Cateel',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Boston',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Lupon',
                'cluster_type' => 'Sub-Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );

        DB::table('clusters')->insert(
            [
                'name' => 'Davao Oriental',
                'cluster_type' => 'Main',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        );
    }
}
