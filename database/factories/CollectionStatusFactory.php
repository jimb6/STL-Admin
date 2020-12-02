<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\CollectionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CollectionStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->state([
                'Pending', 'Approved', 'Closed', 'Short', 'Exceed'
            ]),
            'color_field' => $this->faker->state([
                '#F7B532', '#01C24E', '#F73446', '#6A727B', '#0B95C9'
            ]),
        ];
    }
}
