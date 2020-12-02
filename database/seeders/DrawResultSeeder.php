<?php

namespace Database\Seeders;

use App\Models\DrawResult;
use Illuminate\Database\Seeder;

class DrawResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DrawResult::factory()
            ->count(5)
            ->create();
    }
}
