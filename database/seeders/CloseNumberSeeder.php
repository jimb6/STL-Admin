<?php

namespace Database\Seeders;

use App\Models\CloseNumber;
use Illuminate\Database\Seeder;

class CloseNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CloseNumber::factory()
            ->count(5)
            ->create();
    }
}
