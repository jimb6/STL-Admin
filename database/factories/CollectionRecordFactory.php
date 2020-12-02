<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\CollectionRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CollectionRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'collectionAmount' => $this->faker->randomNumber(2),
//            'collectionDate' => $this->faker->dateTime,
//            'remarks' => $this->faker->text(255),
//            'agent_id' => \App\Models\Agent::factory(),
//            'collection_status_id' => \App\Models\CollectionStatus::factory(),
        ];
    }
}
