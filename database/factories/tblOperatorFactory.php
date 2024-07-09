<?php

namespace Database\Factories;

use App\Models\tblWarehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class tblOperatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fkWarehouseIDNo' => tblWarehouse::factory(),
            'contactPhone' => $this->faker->phoneNumber,
            'isOwner' => $this->faker->boolean,
        ];
    }
}
