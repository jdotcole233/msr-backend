<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class tblActorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'contactPhone' => $this->faker->phoneNumber(),
            // 'ageGroup' => $this->faker->name(),
            'isMale' => $this->faker->boolean(),
            'region' => $this->faker->city(),
            'townCity' => $this->faker->address(),
            'district' => $this->faker->city(),
        ];
    }
}
