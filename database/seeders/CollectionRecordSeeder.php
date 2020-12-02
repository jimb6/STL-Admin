<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CollectionRecord;

class CollectionRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollectionRecord::factory()
            ->count(5)
            ->create();
    }
}
