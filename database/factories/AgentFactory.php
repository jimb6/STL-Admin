<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Base;
use App\Models\Booth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Agent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(20, 40),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Others']),
            'session_status' => $this->faker->boolean,
            'base_id' => Base::all()->random()->id,
            'password' => Hash::make('password'),
            'booth_id' => Booth::all()->random()->id,
        ];
    }
}
