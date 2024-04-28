<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class tblCommodityFactory extends Factory
{

    private $commodities = ['Maize', 'Sorghum', 'Soy Bean', 'Shea', 'Cowpea', 'Millet'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
              'commodityName' => Arr::random($this->commodities),
               'packingSize' => $this->faker->randomDigitNotZero(),
        ];
    }
}
