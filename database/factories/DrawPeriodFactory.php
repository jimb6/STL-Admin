<?php

namespace Database\Factories;

use App\Models\DrawPeriod;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrawPeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DrawPeriod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'draw_time' => $this->faker->time,
//            'draw_type' => $this->faker->text(255),
        ];
    }
}
