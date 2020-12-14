<?php

namespace Database\Factories;

use App\Models\DrawResult;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrawResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DrawResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'bet_game_id' => \App\Models\Game::factory(),
        ];
    }
}
