<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class tblWarehouseFactory extends Factory
{
    public $regions = [
        "Ashanti",
        "Brong Ahafo",
        "Bono East",
        "Central",
        "Eastern",
        "Greater Accra",
        "Northern",
        "Upper East",
        "Upper West",
        "Volta",
        "Western",
        "Savannah",
        "Oti",
        "Ahafo",
        "Western North",
        "North East"
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'warehouseIDNo' => Str::upper(Str::random(5)), 
        'registeredName' => $this->faker->name(), 
        'region' => Arr::random($this->regions),
        'yearEstablished' => $this->faker->year(), 
        'storageCapacity' => $this->faker->randomDigit(), 
        'offersCleaning' => $this->faker->boolean(),
        'offersRebagging' => $this->faker->boolean(), 
        'mainContactName' => $this->faker->name(), 
        'mainContactIsMale' => $this->faker->boolean(), 
        'mainContactPosition' => $this->faker->jobTitle(),
        'mainContactTel' => $this->faker->phoneNumber(), 
        'mainContactEmail' => $this->faker->email(), 
        'declarationAccepted' => $this->faker->boolean(),
        'GPSLat' =>  $this->faker->latitude(),
        'GPSLong' => $this->faker->longitude(),
        ];
    }
}
