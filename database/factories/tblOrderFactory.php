<?php

namespace Database\Factories;

use App\Utility\MsrUtility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class tblOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $isBuyOrder = Arr::random([0, 1]);
        $transactionType = Arr::random(['STORAGE', 'BUY ORDER', 'WITHDRAWAL', 'OFFTAKE']);
        $commodities = Arr::random([
            ['name' => 'Soy Bean', 'id' => 570],
            ['name' => 'Maize', 'id' => 571],
            ['name' => 'Sorghum', 'id' => 572],
            ['name' => 'Cowpea', 'id' => 573],
            ['name' => 'Shea', 'id' => 574]
        ]);
        $harvest_type = Arr::random(['Mechanical threshing', 'Manual threshing']);
        
        $orderDetails = [
            "duration" => strcmp($transactionType, "STORAGE") == 0 ? random_int(1, 10) : "",
            "quantity" => random_int(10, 100),
            "package_size" => random_int(10, 100),
            "commodityName" => $commodities['name'],
            "commodityId" => $commodities['id'],
            "unit_price" => strcmp($transactionType, "STORAGE") != 0 ? \random_int(100, 1000) : "",
            "grnID" => strcmp($transactionType, "WITHDRAWAL") == 0 ? Str::random(10) : "",
            'harvest_type' =>strcmp($transactionType, "STORAGE") == 0 ?  $harvest_type : "",
            'isComplete' => MsrUtility::$UNCOMPLETED
        ];

        return [
            // 'user_id' => 15,
            'fkWarehouseIDNo' => "YNIB9",
            'fkActorID' => \random_int(5, 10),
            'contactPhone' => $this->faker->phoneNumber(),
            'isBuyOrder' => strcmp($transactionType, "BUY ORDER") == 0 ? 1 : 0,
            'transactionType' => $transactionType,
            'orderDetails' => json_encode($orderDetails)
        ];
    }
}
