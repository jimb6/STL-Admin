<?php

namespace Database\Factories;

use App\Models\Base;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Base::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(255),
        ];
    }
}
