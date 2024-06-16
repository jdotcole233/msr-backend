<?php

namespace Database\Seeders;

use App\Models\tblCommodity;
use App\Models\tblOrder;
use App\Models\tblWarehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // tblWarehouse::factory()
        // ->has(tblCommodity::factory()->count(5), 'commodities')
        // ->count(16)
        // ->create();

        tblOrder::factory()
        ->count(20)
        ->create();
    }
}
