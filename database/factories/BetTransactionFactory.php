<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\BetTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BetTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'agent_id' => \App\Models\Agent::factory(),
        ];
    }
}
